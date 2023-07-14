/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package rs.etf.sab.student;

import java.math.BigDecimal;
import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.AbstractList;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import rs.etf.sab.operations.GeneralOperations;
import rs.etf.sab.operations.OrderOperations;

/**
 *
 * @author zeljk
 */
public class uz200073_OrderOperations implements OrderOperations {

    private Connection connection = DB.getInstance().getConnection();
    private GeneralOperations generalOperations;

    public uz200073_OrderOperations(GeneralOperations go) {
        generalOperations = go;
    }

    @Override
    public int addArticle(int orderId, int articleId, int count) {
        int kolicina = -1;
        String sqlKolicina = "select Kolicina from Oglasava where IdArt = ? ";
        try (PreparedStatement psKolicina = connection.prepareStatement(sqlKolicina)) {
            psKolicina.setInt(1, articleId);
            try (ResultSet rsKolicina = psKolicina.executeQuery()) {
                if (rsKolicina.next()) {
                    kolicina = rsKolicina.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        if (kolicina - count < 0) {
            return -1;
        }

        /* Oduzimanje kolicine iz oglasava.. ipak da se radi iz completeOrder */
//        String sqlPovecajKolicinuOglasava = "update Oglasava set Kolicina = Kolicina - ? where IdArt = ? ";
//        try (PreparedStatement psPovecajKolicinuOglasava = connection.prepareStatement(sqlPovecajKolicinuOglasava)){
//            psPovecajKolicinuOglasava.setInt(1, count);
//            psPovecajKolicinuOglasava.setInt(2, articleId);
//            if (psPovecajKolicinuOglasava.executeUpdate() < 1){
//                return -1;
//            }
//        }
//        catch (SQLException ex) {
//            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
//            return -1;
//        }
        String sqlPostojiUPorudzbini = "select IdSad from Sadrzi where IdPor = ? and IdArt = ? ";
        try (PreparedStatement psPostojiUPorudzbini = connection.prepareStatement(sqlPostojiUPorudzbini)) {
            psPostojiUPorudzbini.setInt(1, orderId);
            psPostojiUPorudzbini.setInt(2, articleId);
            try (ResultSet rsPostojiUPorudzbini = psPostojiUPorudzbini.executeQuery()) {
                if (rsPostojiUPorudzbini.next()) {
                    String sqlPovecajKolicinuUPorudzbini = "update Sadrzi set Kolicina = Kolicina + ? where IdPor = ? and IdArt = ? ";
                    try (PreparedStatement psPovecajKolicinuUPorudzbini = connection.prepareStatement(sqlPovecajKolicinuUPorudzbini)) {
                        psPovecajKolicinuUPorudzbini.setInt(1, count);
                        psPovecajKolicinuUPorudzbini.setInt(2, orderId);
                        psPovecajKolicinuUPorudzbini.setInt(3, articleId);
                        if (psPovecajKolicinuUPorudzbini.executeUpdate() < 1) {
                            return -1;
                        }
                        else {
                            return rsPostojiUPorudzbini.getInt(1);
                        }
                    }
                }
                else {
                    String sqlDodajUPorudzbinu = "insert into Sadrzi (Kolicina, IdPor, IdArt) values ( ? , ? , ? )";
                    try (PreparedStatement psDodajUPorudzbinu = connection.prepareStatement(sqlDodajUPorudzbinu, PreparedStatement.RETURN_GENERATED_KEYS)) {
                        psDodajUPorudzbinu.setInt(1, count);
                        psDodajUPorudzbinu.setInt(2, orderId);
                        psDodajUPorudzbinu.setInt(3, articleId);
                        if (psDodajUPorudzbinu.executeUpdate() < 1) {
                            return -1;
                        }
                        else {
                            try (ResultSet rsDodajUPorudzbinu = psDodajUPorudzbinu.getGeneratedKeys()) {
                                rsDodajUPorudzbinu.next();
                                return rsDodajUPorudzbinu.getInt(1);
                            }
                        }
                    }
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

    }

    @Override
    public int removeArticle(int orderId, int articleId) {
        int kolicina = -1;
        String sqlKolicina = "select Kolicina from Oglasava where IdArt = ? ";
        try (PreparedStatement psKolicina = connection.prepareStatement(sqlKolicina)) {
            psKolicina.setInt(1, articleId);
            try (ResultSet rsKolicina = psKolicina.executeQuery()) {
                if (rsKolicina.next()) {
                    kolicina = rsKolicina.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        /* vracanje artikala u prodavnicu */
//        String sqlPovecajKolicinuOglasava = "update Oglasava set Kolicina = Kolicina + ? where IdArt = ? ";
//        try (PreparedStatement psPovecajKolicinuOglasava = connection.prepareStatement(sqlPovecajKolicinuOglasava)){
//            psPovecajKolicinuOglasava.setInt(1, kolicina);
//            psPovecajKolicinuOglasava.setInt(2, articleId);
//            if (psPovecajKolicinuOglasava.executeUpdate() < 1){
//                return -1;
//            }
//        }
//        catch (SQLException ex) {
//            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
//            return -1;
//        }
        String sqlObrisi = "delete from Sadrzi where IdPor = ? and IdArt = ? ";
        try (PreparedStatement psObrisi = connection.prepareStatement(sqlObrisi)) {
            psObrisi.setInt(1, orderId);
            psObrisi.setInt(2, articleId);
            if (psObrisi.executeUpdate() < 1) {
                return -1;
            }
            else {
                return 1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public List<Integer> getItems(int orderId) {
        List<Integer> artikli = new ArrayList<Integer>();
        String sqlSadrzi = "select IdSad from Sadrzi where IdPor = ? ";
        try (PreparedStatement psSadrzi = connection.prepareStatement(sqlSadrzi)) {
            psSadrzi.setInt(1, orderId);
            try (ResultSet rsSadrzi = psSadrzi.executeQuery()) {
                while (rsSadrzi.next()) {
                    artikli.add(rsSadrzi.getInt(1));
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return artikli;
    }

    @Override
    public int completeOrder(int orderId) {
        // TODO OrderOperations.completeOrder mozda ne radi
        int idGraKup = -1;
        int idKup = -1;
        BigDecimal cenaPorudzbine = getFinalPrice(orderId);
        String sqlProveraNovca = "select k.IdGra, k.IdKup from Porudzbina p join Kupac k on k.IdKup = p.IdKup where p.IdPor = ? and k.Novac >= ? ";
        try (PreparedStatement psProveraNovca = connection.prepareStatement(sqlProveraNovca)) {
            psProveraNovca.setInt(1, orderId);
            psProveraNovca.setBigDecimal(2, cenaPorudzbine);
            try (ResultSet rsProveraNovca = psProveraNovca.executeQuery()) {
                if (rsProveraNovca.next()) {
                    idGraKup = rsProveraNovca.getInt(1);
                    idKup = rsProveraNovca.getInt(2);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        
        
        // ako se slucajno desi da se odmah oduzima kolicina iz oglasava pri dodavanju u porudzbinu, onda se ovaj deo brise
        // a u remove i add se otkomentarise
        String sqlArtikliUPorudzbini = "select IdArt, Kolicina from Sadrzi where IdPor = ? ";
        try (PreparedStatement psArtikliUPorudzbini = connection.prepareStatement(sqlArtikliUPorudzbini)) {
            psArtikliUPorudzbini.setInt(1, orderId);
            try (ResultSet rsArtikliUPorudzbini = psArtikliUPorudzbini.executeQuery()) {
                while (rsArtikliUPorudzbini.next()) {
                    int idArt = rsArtikliUPorudzbini.getInt(1);
                    int kolicina = rsArtikliUPorudzbini.getInt(2);
                    String sqlProveraKolicine = "select IdArt, Kolicina from Oglasava where IdArt = ? and Kolicina >= ? ";
                    try (PreparedStatement psProveraKolicine = connection.prepareStatement(sqlProveraKolicine)) {
                        psProveraKolicine.setInt(1, idArt);
                        psProveraKolicine.setInt(2, kolicina);
                        try (ResultSet rsProveraKolicine = psProveraKolicine.executeQuery()) {
                            if (!rsProveraKolicine.next()) {
                                return -1;
                            }
                        }
                    }
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        String sqlUbaciTransakciju = "insert into Transakcija (Novac, Datum, IdPor, IdPro, IdKup) values ( ? , ? , ? , null , ? )";
        try (PreparedStatement psUbaciTransakciju = connection.prepareStatement(sqlUbaciTransakciju)) {
            psUbaciTransakciju.setBigDecimal(1, cenaPorudzbine);
            psUbaciTransakciju.setTimestamp(2, new Timestamp(generalOperations.getCurrentTime().getTimeInMillis()));
            psUbaciTransakciju.setInt(3, orderId);
            psUbaciTransakciju.setInt(4, idKup);
            if (psUbaciTransakciju.executeUpdate() < 1) {
                return -1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        String sqlSmanjiNovac = "update Kupac set Novac = Novac - ? where IdKup = ? ";
        try (PreparedStatement psSmanjiNovac = connection.prepareStatement(sqlSmanjiNovac)) {
            psSmanjiNovac.setBigDecimal(1, cenaPorudzbine);
            psSmanjiNovac.setInt(2, idKup);
            if (psSmanjiNovac.executeUpdate() < 1) {
                return -1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        // azuriranje kolicine artikala
        // ako se desi da se oduzima kolicina u addArticle ovo se brise
        try (PreparedStatement psArtikliUPorudzbini = connection.prepareStatement(sqlArtikliUPorudzbini)) {
            psArtikliUPorudzbini.setInt(1, orderId);
            try (ResultSet rsArtikliUPorudzbini = psArtikliUPorudzbini.executeQuery()) {
                while (rsArtikliUPorudzbini.next()) {
                    int idArt = rsArtikliUPorudzbini.getInt(1);
                    int kolicina = rsArtikliUPorudzbini.getInt(2);
                    String sqlAzurirajKolicinuUProdavnicama = "update Oglasava set Kolicina = Kolicina - ? where IdArt = ? ";
                    try (PreparedStatement psAzurirajKolicinuUProdavnicama = connection.prepareStatement(sqlAzurirajKolicinuUProdavnicama)) {
                        psAzurirajKolicinuUProdavnicama.setInt(1, kolicina);
                        psAzurirajKolicinuUProdavnicama.setInt(2, idArt);
                        if (psAzurirajKolicinuUProdavnicama.executeUpdate() < 1) {
                            return -1;
                        }
                    }
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        // izracunavanje grada spajanja
        // ako najkraca putanja vrati null znaci da grad kupca ima prodavnicu i to je sigurno grad spajanja :)
        int minDistanca = Integer.MAX_VALUE;
        int gradSpajanja = -1;
        String sqlGradoviSaProdavnicama = "select distinct IdGra from Prodavnica";
        try (PreparedStatement psGradoviSaProdavnicama = connection.prepareStatement(sqlGradoviSaProdavnicama);
                ResultSet rsGradoviSaProdavnicama = psGradoviSaProdavnicama.executeQuery()) {
            uz200073_GeneralOperations go = new uz200073_GeneralOperations();
            while (rsGradoviSaProdavnicama.next()) {
                int idGra = rsGradoviSaProdavnicama.getInt(1);
                String putanja = go.najkracaPutanja(idGraKup, idGra);
                if (putanja == null) {
                    gradSpajanja = idGra;
                    break;
                }
                if (minDistanca > Integer.parseInt(putanja.split("-")[1])) {
                    gradSpajanja = idGra;
                    minDistanca = Integer.parseInt(putanja.split("-")[1]);
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        // azuriranje u kom se gradu nalaze artikli i brDana
        int brojArtikala = 0;
        int brojArtikalaUGraduSpajanja = 0;
        try (PreparedStatement psArtikliUPorudzbini = connection.prepareStatement(sqlArtikliUPorudzbini)) {
            psArtikliUPorudzbini.setInt(1, orderId);
            try (ResultSet rsArtikliUPorudzbini = psArtikliUPorudzbini.executeQuery()) {
                while (rsArtikliUPorudzbini.next()) {
                    brojArtikala++;
                    int idArt = rsArtikliUPorudzbini.getInt(1);
                    int kolicina = rsArtikliUPorudzbini.getInt(2);
                    String sqlGradArtikla = "select p.IdGra from Prodavnica p join Oglasava o on p.IdPro = o.IdPro "
                            + " join Artikal a on a.IdArt = o.IdArt "
                            + " where a.IdArt = ?";
                    try (PreparedStatement psGradArtikla = connection.prepareStatement(sqlGradArtikla)) {
                        psGradArtikla.setInt(1, idArt);
                        try (ResultSet rsGradArtikla = psGradArtikla.executeQuery()) {
                            if (!rsGradArtikla.next()){
                                return -1;
                            }
                            int idGra = rsGradArtikla.getInt(1);
                            String sqlPostaviPocetniGrad = "update Sadrzi set BrDana = 0, IdGra = ? where IdPor = ? and IdArt = ? ";
                            try (PreparedStatement psPostaviPocetniGrad = connection.prepareStatement(sqlPostaviPocetniGrad)) {
                                psPostaviPocetniGrad.setInt(1, idGra);
                                psPostaviPocetniGrad.setInt(2, orderId);
                                psPostaviPocetniGrad.setInt(3, idArt);
                                if (psPostaviPocetniGrad.executeUpdate() < 1) {
                                    return -1;
                                }
                            }
                            if (idGra == gradSpajanja) {
                                brojArtikalaUGraduSpajanja++;
                            }
                        }
                    }

                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }

        String sqlPosaljiPorudzbinu = "update Porudzbina set Stanje = ?, VremeSlanja = ?, VremePrijema = ?, IdGra = ? where IdPor = ? ";
        try (PreparedStatement psPosaljiPorudzbinu = connection.prepareStatement(sqlPosaljiPorudzbinu)) {
            if (idGraKup == gradSpajanja && brojArtikala == brojArtikalaUGraduSpajanja) {
                psPosaljiPorudzbinu.setString(1, "arrived");
                psPosaljiPorudzbinu.setTimestamp(3, new Timestamp(generalOperations.getCurrentTime().getTimeInMillis()));
            }
            else if (idGraKup != gradSpajanja && brojArtikala == brojArtikalaUGraduSpajanja) {
                psPosaljiPorudzbinu.setString(1, "assembled");
                psPosaljiPorudzbinu.setNull(3, java.sql.Types.TIMESTAMP);
            }
            else {
                psPosaljiPorudzbinu.setString(1, "sent");
                psPosaljiPorudzbinu.setNull(3, java.sql.Types.TIMESTAMP);
            }
            psPosaljiPorudzbinu.setTimestamp(2, new Timestamp(generalOperations.getCurrentTime().getTimeInMillis()));
            psPosaljiPorudzbinu.setInt(4, gradSpajanja);
            psPosaljiPorudzbinu.setInt(5, orderId);
            if (psPosaljiPorudzbinu.executeUpdate() < 1) {
                return -1;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
        return 1;
    }

    @Override
    public BigDecimal getFinalPrice(int orderId) {
        String sqlCena = " { call SP_FINAL_PRICE ( ? , ? , ? ) } ";
        try (CallableStatement csCena = connection.prepareCall(sqlCena)) {
            csCena.setInt(1, orderId);
            csCena.setTimestamp(2, new Timestamp(generalOperations.getCurrentTime().getTimeInMillis()));
            csCena.registerOutParameter(3, java.sql.Types.DECIMAL);
            csCena.execute();
            return csCena.getBigDecimal(3).setScale(3);
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public BigDecimal getDiscountSum(int orderId) {
        String sqlCena = " { call SP_CENA_BEZ_POPUSTA ( ? , ? ) } ";
        try (CallableStatement csCena = connection.prepareCall(sqlCena)) {
            csCena.setInt(1, orderId);
            csCena.registerOutParameter(2, java.sql.Types.DECIMAL);
            csCena.execute();
            BigDecimal finalnaCena = this.getFinalPrice(orderId);
            if (finalnaCena != null) {
                return csCena.getBigDecimal(2).subtract(finalnaCena).setScale(3);
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public String getState(int orderId) {

        String sqlStatus = "select Stanje from Porudzbina where IdPor = ? ";
        try (PreparedStatement psStatus = connection.prepareStatement(sqlStatus)) {
            psStatus.setInt(1, orderId);
            try (ResultSet rsStatus = psStatus.executeQuery()) {
                if (rsStatus.next()) {
                    return rsStatus.getString(1);
                }
                else {
                    return ""; // nije specificirano pri failure
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return "";
        }
    }

    @Override
    public Calendar getSentTime(int orderId) {
        String sqlVremeSlanja = "select VremeSlanja from Porudzbina where IdPor = ? ";
        try (PreparedStatement psVremeSlanja = connection.prepareStatement(sqlVremeSlanja)) {
            psVremeSlanja.setInt(1, orderId);
            try (ResultSet rsVremeSlanja = psVremeSlanja.executeQuery()) {
                if (rsVremeSlanja.next()) {
                    Calendar c = Calendar.getInstance();
                    if (rsVremeSlanja.getTimestamp(1) == null) {
                        return null;
                    }
                    c.setTimeInMillis(rsVremeSlanja.getTimestamp(1).getTime());
                    return c;
                }
                else {
                    return null;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public Calendar getRecievedTime(int orderId) {
        String sqlVremePrijema = "select VremePrijema from Porudzbina where IdPor = ? ";
        try (PreparedStatement psVremePrijema = connection.prepareStatement(sqlVremePrijema)) {
            psVremePrijema.setInt(1, orderId);
            try (ResultSet rsVremePrijema = psVremePrijema.executeQuery()) {
                if (rsVremePrijema.next()) {
                    Calendar c = Calendar.getInstance();
                    if (rsVremePrijema.getTimestamp(1) == null){
                        return null;
                    }
                    c.setTimeInMillis(rsVremePrijema.getTimestamp(1).getTime());
                    return c;
                }
                else {
                    return null;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public int getBuyer(int orderId) {
        String sqlKupac = "select IdKup from Porudzbina where IdPor = ? ";
        try (PreparedStatement psKupac = connection.prepareStatement(sqlKupac)) {
            psKupac.setInt(1, orderId);
            try (ResultSet rsKupac = psKupac.executeQuery()) {
                if (rsKupac.next()) {
                    return rsKupac.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public int getLocation(int orderId) {

        if (getState(orderId).equals("created")) {
            return -1;
        }
        else if (getState(orderId).equals("assembled")){
            String sqlGrad = "select distinct s.IdGra from Porudzbina p join Sadrzi s on s.IdPor = p.IdPor where p.IdPor = ? ";
            try (PreparedStatement psGrad = connection.prepareStatement(sqlGrad)){
                psGrad.setInt(1, orderId);
                try (ResultSet rsGrad = psGrad.executeQuery()){
                    if (rsGrad.next()){
                        return rsGrad.getInt(1);
                    }
                }
            }
            catch (SQLException ex) {
                Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
        else if (getState(orderId).equals("sent")){
            String sqlGrad = "select p.IdGra from Porudzbina p where p.IdPor = ? ";
            try (PreparedStatement psGrad = connection.prepareStatement(sqlGrad)){
                psGrad.setInt(1, orderId);
                try (ResultSet rsGrad = psGrad.executeQuery()){
                    if (rsGrad.next()){
                        return rsGrad.getInt(1);
                    }
                }
            }
            catch (SQLException ex) {
                Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
        else {
            String sqlGrad = "select k.IdGra from Kupac k join Porudzbina p on p.IdKup = k.IdKup where p.IdPor = ? ";
            try (PreparedStatement psGrad = connection.prepareStatement(sqlGrad)){
                psGrad.setInt(1, orderId);
                try (ResultSet rsGrad = psGrad.executeQuery()){
                    if (rsGrad.next()){
                        return rsGrad.getInt(1);
                    }
                }
            }
            catch (SQLException ex) {
                Logger.getLogger(uz200073_OrderOperations.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
        return 0;
    }

}
