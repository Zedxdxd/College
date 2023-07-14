/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package rs.etf.sab.student;

import java.math.BigDecimal;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import rs.etf.sab.operations.BuyerOperations;

/**
 *
 * @author zeljk
 */
public class uz200073_BuyerOperations implements BuyerOperations {

    private Connection connection = DB.getInstance().getConnection();

    @Override
    public int createBuyer(String name, int cityId) {
        String sqlPostojiGrad = "select * from Grad where IdGra = ?";
        try (PreparedStatement psPostojiGrad = connection.prepareStatement(sqlPostojiGrad)) {
            psPostojiGrad.setInt(1, cityId);
            try (ResultSet rsPostojiGrad = psPostojiGrad.executeQuery()) {
                if (!rsPostojiGrad.next()) {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        int idKup = -1;
        String sqlNapraviKupca = "insert into Kupac (Naziv, IdGra, Novac) values ( ? , ? , 0 )";
        try (PreparedStatement psNapraviKupca = connection.prepareStatement(sqlNapraviKupca, PreparedStatement.RETURN_GENERATED_KEYS)) {
            psNapraviKupca.setString(1, name);
            psNapraviKupca.setInt(2, cityId);
            psNapraviKupca.execute();
            try (ResultSet rsNapraviKupca = psNapraviKupca.getGeneratedKeys()) {
                if (rsNapraviKupca.next()) {
                    idKup = rsNapraviKupca.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        return idKup;

    }

    @Override
    public int setCity(int buyerId, int cityId) {
        String sqlPromeniGrad = "update Kupac set IdGra = ? where IdKup = ? ";
        try (PreparedStatement psPromeniGrad = connection.prepareStatement(sqlPromeniGrad)){
            psPromeniGrad.setInt(1, cityId);
            psPromeniGrad.setInt(2, buyerId);
            if (psPromeniGrad.executeUpdate() < 1){
                return -1;
            }
            else {
                return 1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public int getCity(int buyerId) {
        String sqlGrad = "select IdGra from Kupac where IdKup = ? ";
        try (PreparedStatement psGrad = connection.prepareStatement(sqlGrad)) {
            psGrad.setInt(1, buyerId);
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
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        
    }

    @Override
    public BigDecimal increaseCredit(int buyerId, BigDecimal credit) {
        String sqlPovecajNovac = "update Kupac set Novac = Novac + ? where IdKup = ? ";
        try (PreparedStatement psPovecajNovac = connection.prepareStatement(sqlPovecajNovac)){
            psPovecajNovac.setBigDecimal(1, credit.setScale(3));
            psPovecajNovac.setInt(2, buyerId);
            if (psPovecajNovac.executeUpdate() < 1){
                return new BigDecimal(-1).setScale(3);
            }
            else {
                String sqlNovac = "select Novac from Kupac where IdKup = ? ";
                try (PreparedStatement psNovac = connection.prepareStatement(sqlNovac)){
                    psNovac.setInt(1, buyerId);
                    try (ResultSet rsNovac = psNovac.executeQuery()){
                        if (!rsNovac.next()){
                            return new BigDecimal(-1).setScale(3);
                        }
                        else {
                            return rsNovac.getBigDecimal(1).setScale(3);
                        }
                    }
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return new BigDecimal(-1);
        }
    }

    @Override
    public int createOrder(int buyerId) {

        String sqlPostojiKupac = "select * from Kupac where IdKup = ? ";
        try (PreparedStatement psPostojiKupac = connection.prepareStatement(sqlPostojiKupac)) {
            psPostojiKupac.setInt(1, buyerId);
            try (ResultSet rsPostojiKupac = psPostojiKupac.executeQuery()) {
                if (!rsPostojiKupac.next()) {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        String sqlNapraviPorudzbinu = "insert into Porudzbina (IdKup, Stanje) values ( ? , 'created')";
        try (PreparedStatement psNapraviPorudzbinu = connection.prepareStatement(sqlNapraviPorudzbinu, PreparedStatement.RETURN_GENERATED_KEYS)) {
            psNapraviPorudzbinu.setInt(1, buyerId);
            psNapraviPorudzbinu.execute();
            try (ResultSet rsNapraviPorudzbinu = psNapraviPorudzbinu.getGeneratedKeys()){
                if (rsNapraviPorudzbinu.next()){
                    return rsNapraviPorudzbinu.getInt(1);
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        return -1;
    }

    @Override
    public List<Integer> getOrders(int buyerId) {
        List<Integer> porudzbine = new ArrayList<>();
        
        String sqlDohvatiPorudzbine = "select IdPor from Porudzbina where IdKup = ? ";
        try (PreparedStatement psDohvatiPorudzbine = connection.prepareStatement(sqlDohvatiPorudzbine)) {
            psDohvatiPorudzbine.setInt(1, buyerId);
            try (ResultSet rsDohvatiPorudzbine = psDohvatiPorudzbine.executeQuery()){
                while (rsDohvatiPorudzbine.next()){
                    porudzbine.add(rsDohvatiPorudzbine.getInt(1));
                }
                return porudzbine;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return null;
        }
    }

    @Override
    public BigDecimal getCredit(int buyerId) {
        String sqlDohvatiNovac = "select Novac from Kupac where IdKup = ? ";
        try (PreparedStatement psDohvatiNovac = connection.prepareStatement(sqlDohvatiNovac)) {
            psDohvatiNovac.setInt(1, buyerId);
            try (ResultSet rsDohvatiNovac = psDohvatiNovac.executeQuery()){
                if (rsDohvatiNovac.next()){
                    return rsDohvatiNovac.getBigDecimal(1).setScale(3);
                }
                else {
                    return new BigDecimal(-1);
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_BuyerOperations.class.getName()).log(Level.SEVERE, null, ex);
            return new BigDecimal(-1);
        }
    }

}
