/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package rs.etf.sab.student;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import rs.etf.sab.operations.ShopOperations;

/**
 *
 * @author zeljk
 */
public class uz200073_ShopOperations implements ShopOperations {

    private Connection connection = DB.getInstance().getConnection();

    @Override
    public int createShop(String name, String cityName) {
        String sqlPostojiProdavnica = "select * from Prodavnica where Ime = ? ";
        try (PreparedStatement psPostojiProdavnica = connection.prepareStatement(sqlPostojiProdavnica)) {
            psPostojiProdavnica.setString(1, name);
            try (ResultSet rsPostojiProdavnica = psPostojiProdavnica.executeQuery()) {
                if (rsPostojiProdavnica.next()) {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        String sqlGrad = "select IdGra from Grad where Ime = ? ";
        int idGra = -1;
        try (PreparedStatement psGrad = connection.prepareStatement(sqlGrad)) {
            psGrad.setString(1, cityName);
            try (ResultSet rsGrad = psGrad.executeQuery()) {
                if (rsGrad.next()) {
                    idGra = rsGrad.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        String sqlUbaciProdavnicu = "insert into Prodavnica (Ime, IdGra, Popust) values ( ? , ? , 0 ) ";
        try (PreparedStatement psUbaciProdavnicu = connection.prepareStatement(sqlUbaciProdavnicu, PreparedStatement.RETURN_GENERATED_KEYS)) {
            psUbaciProdavnicu.setString(1, name);
            psUbaciProdavnicu.setInt(2, idGra);
            if (psUbaciProdavnicu.executeUpdate() < 1) {
                return -1;
            }
            else {
                try (ResultSet rsUbaciProdavnicu = psUbaciProdavnicu.getGeneratedKeys()) {
                    rsUbaciProdavnicu.next();
                    return rsUbaciProdavnicu.getInt(1);
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public int setCity(int shopId, String cityName) {
        
        String sqlGrad = "select IdGra from Grad where Ime = ? ";
        int idGra = -1;
        try (PreparedStatement psGrad = connection.prepareStatement(sqlGrad)) {
            psGrad.setString(1, cityName);
            try (ResultSet rsGrad = psGrad.executeQuery()) {
                if (rsGrad.next()) {
                    idGra = rsGrad.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        
        String sqlPostaviGrad = "update Prodavnica set IdGra = ? where IdPro = ? ";
        try (PreparedStatement psPostaviGrad = connection.prepareStatement(sqlPostaviGrad)){
            psPostaviGrad.setInt(1, idGra);
            psPostaviGrad.setInt(2, shopId);
            if (psPostaviGrad.executeUpdate() < 1){
                return -1;
            }
            else {
                return 1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public int getCity(int shopId) {
        String sqlGrad = "select IdGra from Prodavnica where IdPro = ? ";
        try (PreparedStatement psGrad = connection.prepareStatement(sqlGrad)) {
            psGrad.setInt(1, shopId);
            try (ResultSet rsGrad = psGrad.executeQuery()) {
                if (!rsGrad.next()) {
                    return -1;
                }
                else {
                    return rsGrad.getInt(1);
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        
        
    }

    @Override
    public int setDiscount(int shopId, int discountPercentage) {
        String sqlPromeniPopust = "update Prodavnica set Popust = ? where IdPro = ? ";
        try (PreparedStatement psPromeniPopust = connection.prepareStatement(sqlPromeniPopust)){
            psPromeniPopust.setInt(1, discountPercentage);
            psPromeniPopust.setInt(2, shopId);
            if (psPromeniPopust.executeUpdate() < 1){
                return -1;
            }
            else {
                return 1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public int increaseArticleCount(int articleId, int increment) {
        String sqlPovecaj = "update Oglasava set Kolicina = Kolicina + ? where IdArt = ? ";
        try(PreparedStatement psPovecaj = connection.prepareStatement(sqlPovecaj)){
            psPovecaj.setInt(1, increment);
            psPovecaj.setInt(2, articleId);
            if (psPovecaj.executeUpdate() < 1){
                return -1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        
        String sqlKolicina = "select Kolicina from Oglasava where IdArt = ? ";
        try(PreparedStatement psKolicina = connection.prepareStatement(sqlKolicina)){
            psKolicina.setInt(1, articleId);
            try (ResultSet rsKolicina = psKolicina.executeQuery()){
                if (rsKolicina.next()) {
                    return rsKolicina.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public int getArticleCount(int articleId) {
        String sqlKolicina = "select Kolicina from Oglasava where IdArt = ? ";
        try(PreparedStatement psKolicina = connection.prepareStatement(sqlKolicina)){
            psKolicina.setInt(1, articleId);
            try (ResultSet rsKolicina = psKolicina.executeQuery()){
                if (rsKolicina.next()) {
                    return rsKolicina.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public List<Integer> getArticles(int shopId) {
        List<Integer> artikli = new ArrayList<>();
        String sqlArtikli = "select IdArt from Oglasava where IdPro = ? ";
        try (PreparedStatement psArtikli = connection.prepareStatement(sqlArtikli)){
            psArtikli.setInt(1, shopId);
            try (ResultSet rsArtikli = psArtikli.executeQuery()){
                while (rsArtikli.next()){
                    artikli.add(rsArtikli.getInt(1));
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return artikli;
    }

    @Override
    public int getDiscount(int shopId) {
        String sqlPopust = "select Popust from Prodavnica where IdPro = ? ";
        try (PreparedStatement psPopust = connection.prepareStatement(sqlPopust)){
            psPopust.setInt(1, shopId);
            try (ResultSet rsPopust = psPopust.executeQuery()){
                if (rsPopust.next()){
                    return rsPopust.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ShopOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return -1;
    }

}
