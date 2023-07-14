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
import java.util.logging.Level;
import java.util.logging.Logger;
import rs.etf.sab.operations.ArticleOperations;

/**
 *
 * @author zeljk
 */
public class uz200073_ArticleOperations implements ArticleOperations {

    private Connection connection = DB.getInstance().getConnection();

    @Override
    public int createArticle(int shopId, String articleName, int articlePrice) {

        String sqlPostojiProdavnica = "select * from Prodavnica where IdPro = ? ";
        try (PreparedStatement psPostojiProdavnica = connection.prepareStatement(sqlPostojiProdavnica)) {
            psPostojiProdavnica.setInt(1, shopId);
            try (ResultSet rsPostojiProdavnica = psPostojiProdavnica.executeQuery()){
                if (!rsPostojiProdavnica.next()){
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ArticleOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        
        int idArt = -1;
        String sqlNapraviArtikal = "insert into Artikal (Naziv, Cena) values ( ? , ? )";
        try (PreparedStatement psNapraviArtikal = connection.prepareStatement(sqlNapraviArtikal, PreparedStatement.RETURN_GENERATED_KEYS)) {
            psNapraviArtikal.setString(1, articleName);
            psNapraviArtikal.setInt(2, articlePrice);
            psNapraviArtikal.execute();
            try (ResultSet rsNapraviArtikal = psNapraviArtikal.getGeneratedKeys()) {
                if (rsNapraviArtikal.next()) {
                    idArt = rsNapraviArtikal.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ArticleOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        
        String sqlOglasava = "insert into Oglasava (IdArt, IdPro, Kolicina) values ( ? , ? , 0 )";
        try (PreparedStatement psOglasava = connection.prepareStatement(sqlOglasava)) {
            psOglasava.setInt(1, idArt);
            psOglasava.setInt(2, shopId);
            if (psOglasava.executeUpdate() < 1){
                String sqlObrisiArtikal = "delete from Artikal where IdArt = ?";
                try (PreparedStatement psObrisiArtikal = connection.prepareStatement(sqlObrisiArtikal)) {
                    psObrisiArtikal.setInt(1, idArt);
                    psObrisiArtikal.executeUpdate();
                }
                return -1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_ArticleOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        return idArt;
    }

}
