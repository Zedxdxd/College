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
import java.sql.Statement;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Stack;
import java.util.logging.Level;
import java.util.logging.Logger;
import rs.etf.sab.operations.CityOperations;

/**
 *
 * @author zeljk
 */
public class uz200073_CityOperations implements CityOperations {

    private Connection connection = DB.getInstance().getConnection();

    @Override
    public int createCity(String string) {
        String sqlCheckUnique = "select IdGra from Grad where Ime = ? ";
        String sqlCreate = "insert into Grad (Ime) values (?)";
        try (PreparedStatement psCheckUnique = connection.prepareStatement(sqlCheckUnique);
                PreparedStatement psCreate = connection.prepareStatement(sqlCreate, Statement.RETURN_GENERATED_KEYS)) {
            psCheckUnique.setString(1, string);
            try (ResultSet rsCheckUnique = psCheckUnique.executeQuery()) {
                if (rsCheckUnique.next()) {
                    return -1;
                }
            }
            psCreate.setString(1, string);
            psCreate.executeUpdate();
            try (ResultSet rsCreate = psCreate.getGeneratedKeys()) {
                rsCreate.next();
                return rsCreate.getInt(1);
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_CityOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return -1;
    }

    @Override
    public List<Integer> getCities() {
        String sql = "select IdGra from Grad";
        List<Integer> result = new ArrayList();
        try (PreparedStatement ps = connection.prepareStatement(sql);
                ResultSet rs = ps.executeQuery()) {
            while (rs.next()) {
                result.add(rs.getInt(1));
            }
            return result;
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_CityOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public int connectCities(int cityId1, int cityId2, int distance) {
        if (cityId1 == cityId2) {
            return -1;
        }
        String sqlPostojiLinija = "select * from Linija where IdGra1 = ? and IdGra2 = ? ";
        try (PreparedStatement psPostojiLinija = connection.prepareStatement(sqlPostojiLinija)) {
            psPostojiLinija.setInt(1, cityId1);
            psPostojiLinija.setInt(2, cityId2);
            try (ResultSet rsPostojiLinija = psPostojiLinija.executeQuery()) {
                if (rsPostojiLinija.next()) {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_CityOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        int idLin = -1;
        String sqlUbaciLiniju = "insert into Linija (IdGra1, IdGra2, Distanca) values ( ? , ? , ? )";
        try (PreparedStatement psUbaciLiniju = connection.prepareStatement(sqlUbaciLiniju, PreparedStatement.RETURN_GENERATED_KEYS)) {
            psUbaciLiniju.setInt(1, cityId1);
            psUbaciLiniju.setInt(2, cityId2);
            psUbaciLiniju.setInt(3, distance);
            psUbaciLiniju.execute();
            try (ResultSet rsUbaciLiniju = psUbaciLiniju.getGeneratedKeys()) {
                if (rsUbaciLiniju.next()) {
                    idLin = rsUbaciLiniju.getInt(1);
                }
                else {
                    return -1;
                }
            }
            psUbaciLiniju.setInt(1, cityId2);
            psUbaciLiniju.setInt(2, cityId1);
            psUbaciLiniju.setInt(3, distance);
            psUbaciLiniju.execute();
            try (ResultSet rsUbaciLiniju = psUbaciLiniju.getGeneratedKeys()) {
                if (rsUbaciLiniju.next()) {
                    return idLin;
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_CityOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

    }

    @Override
    public List<Integer> getConnectedCities(int cityId) {
        List<Integer> gradovi = new ArrayList<>();
        /* verzija kada se dohvataju samo susedni gradovi */
        String sqlGradovi = "select IdGra2 from Linija where IdGra1 = ? ";
        try (PreparedStatement psGradovi = connection.prepareStatement(sqlGradovi)) {
            psGradovi.setInt(1, cityId);
            try (ResultSet rsGradovi = psGradovi.executeQuery()) {
                while (rsGradovi.next()) {
                    gradovi.add(rsGradovi.getInt(1));
                }
                return gradovi;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_CityOperations.class.getName()).log(Level.SEVERE, null, ex);
            return gradovi;
        }

        /* verzija kada se dohvataju svi reachable gradovi */
//        Map<Integer, Boolean> poseceni = new HashMap<>();
//        Stack<Integer> stek = new Stack<>();
//        poseceni.put(cityId, true);
//        stek.push(cityId);
//        String sqlGradovi = "select IdGra2 from Linija where IdGra1 = ? ";
//        try (PreparedStatement psGradovi = connection.prepareStatement(sqlGradovi)) {
//            while (!stek.empty()) {
//                int city = stek.pop();
//                psGradovi.setInt(1, city);
//                try (ResultSet rsGradovi = psGradovi.executeQuery()) {
//                    while (rsGradovi.next()) {
//                        if (!poseceni.containsKey(rsGradovi.getInt(1))){
//                            stek.push(rsGradovi.getInt(1));
//                            poseceni.put(rsGradovi.getInt(1), true);
//                            gradovi.add(rsGradovi.getInt(1));
//                        }
//                    }
//                }
//            }
//        }
//        catch (SQLException ex) {
//            Logger.getLogger(uz200073_CityOperations.class.getName()).log(Level.SEVERE, null, ex);
//            return gradovi;
//        }
//        return gradovi;
    }

    @Override
    public List<Integer> getShops(int cityId) {
        String sqlPostojiGrad = "select * from Grad where IdGra = ? ";
        try (PreparedStatement psPostojiGrad = connection.prepareStatement(sqlPostojiGrad)) {
            psPostojiGrad.setInt(1, cityId);
            try (ResultSet rsPostojiGrad = psPostojiGrad.executeQuery()) {
                if (!rsPostojiGrad.next()) {
                    return null;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_CityOperations.class.getName()).log(Level.SEVERE, null, ex);
            return null;
        }

        String sqlProdavnice = "select IdPro from Prodavnica where IdGra = ? ";
        try (PreparedStatement psProdavnice = connection.prepareStatement(sqlProdavnice)) {
            psProdavnice.setInt(1, cityId);
            List<Integer> prodavnice = new ArrayList<>();
            try (ResultSet rsProdavnice = psProdavnice.executeQuery()) {
                while (rsProdavnice.next()) {
                    prodavnice.add(rsProdavnice.getInt(1));
                }
                return prodavnice;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_CityOperations.class.getName()).log(Level.SEVERE, null, ex);
            return null;
        }
    }

}
