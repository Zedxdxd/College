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
import java.sql.Timestamp;
import java.util.Calendar;
import java.util.HashSet;
import java.util.LinkedList;
import java.util.List;
import java.util.Queue;
import java.util.logging.Level;
import java.util.logging.Logger;
import rs.etf.sab.operations.GeneralOperations;
import java.util.Set;

/**
 *
 * @author zeljk
 */
public class uz200073_GeneralOperations implements GeneralOperations {

    private Connection connection = DB.getInstance().getConnection();

    private Calendar calendar;

    @Override
    public void setInitialTime(Calendar time) {
        calendar = Calendar.getInstance();
        calendar.setTimeInMillis(time.getTimeInMillis());
    }

    public String najkracaPutanja(int idGraSrc, int idGraDst) throws SQLException {
        if (idGraSrc == idGraDst) {
            return null;
        }
        else {
            // "dist|gr1,gr2,..."
            String minPutanja = null;
            String putanja = "0-" + idGraSrc;
            Set<Integer> poseceniGradovi = new HashSet<>();
            List<String> red = new LinkedList<>();
            red.add(putanja);
            while (!red.isEmpty()) {
                String trenutna = red.remove(0);
                int distanca = Integer.parseInt(trenutna.split("-")[0]);
                String[] gradovi = trenutna.split("-")[1].split(",");
                int idGraPosl = Integer.parseInt(gradovi[gradovi.length - 1]);

                String sqlLinije = "select IdGra2, Distanca from Linija where IdGra1 = ? ";
                try (PreparedStatement psLinije = connection.prepareStatement(sqlLinije)) {
                    psLinije.setInt(1, idGraPosl);
                    try (ResultSet rsLinije = psLinije.executeQuery()) {
                        while (rsLinije.next()) {
                            int idGra2 = rsLinije.getInt(1);
                            if (poseceniGradovi.contains(idGra2)) {
                                continue;
                            }

                            String novaPutanja = distanca + rsLinije.getInt(2) + "-" + trenutna.split("-")[1] + "," + idGra2;
                            if (idGra2 == idGraDst) {
                                if (minPutanja == null || Integer.parseInt(minPutanja.split("-")[0]) > distanca + rsLinije.getInt(2)) {
                                    minPutanja = novaPutanja;
                                }
                            }
                            red.add(novaPutanja);
                        }
                    }
                }
                poseceniGradovi.add(idGraPosl);
            }
            String sqlLinije = "select Distanca from Linija where IdGra1 = ? and IdGra2 = ? ";
            try (PreparedStatement psLinije = connection.prepareStatement(sqlLinije)) {
                psLinije.setInt(1, Integer.parseInt(minPutanja.split("-")[1].split(",")[0]));
                psLinije.setInt(2, Integer.parseInt(minPutanja.split("-")[1].split(",")[1]));
                try (ResultSet rsLinije = psLinije.executeQuery()) {
                    if (rsLinije.next()) {
                        minPutanja = rsLinije.getInt(1) + "-" + minPutanja;
                    }
                }
            }
            // "distIzmedjuPrvaDva-UkupnaDuzina-gradovi"
            return minPutanja;
        }
    }

    @Override
    public Calendar time(int days) {
        // TODO mozda ne radi
        for (int i = 0; i < days; i++) {

            calendar.add(Calendar.DAY_OF_MONTH, 1);
            String sqlPorudzbine = "select IdPor, IdGra, IdKup, Stanje from Porudzbina where Stanje = 'sent' or Stanje = 'assembled'";
            try (PreparedStatement psPorudzbine = connection.prepareStatement(sqlPorudzbine);
                    ResultSet rsPorudzbine = psPorudzbine.executeQuery()) {
                while (rsPorudzbine.next()) { // iteriranje da se azurira svaka porudzbina
                    int idPor = rsPorudzbine.getInt(1);
                    int gradSpajanja = rsPorudzbine.getInt(2);
                    int idKup = rsPorudzbine.getInt(3);
                    String stanje = rsPorudzbine.getString(4);
                    int idGraKupca = -1;

                    // dohvata se grad kupca sto je destinacija porudzbine
                    String sqlGradKupca = "select IdGra from Kupac where IdKup = ? ";
                    try (PreparedStatement psGradKupca = connection.prepareStatement(sqlGradKupca)) {
                        psGradKupca.setInt(1, idKup);
                        try (ResultSet rsGradKupca = psGradKupca.executeQuery()) {
                            rsGradKupca.next();
                            idGraKupca = rsGradKupca.getInt(1);
                        }
                    }

                    if (stanje.equals("sent")) { // ako je porudzbina poslata mora svaki artikal da se azurira
                        String sqlSadrzi = "select IdGra, IdArt, BrDana, IdSad from Sadrzi where IdPor = ? ";
                        int brojArtikala = 0;
                        int brojArtikalaUGraduSpajanja = 0;
                        try (PreparedStatement psSadrzi = connection.prepareStatement(sqlSadrzi)) {
                            psSadrzi.setInt(1, idPor);
                            try (ResultSet rsSadrzi = psSadrzi.executeQuery()) {
                                while (rsSadrzi.next()) { // iteriranje da se azurira pozicija svakog artikla
                                    int idGraArt = rsSadrzi.getInt(1);
                                    int idArt = rsSadrzi.getInt(2);
                                    int brDana = rsSadrzi.getInt(3);
                                    int idSad = rsSadrzi.getInt(4);
                                    String putanja = najkracaPutanja(idGraArt, gradSpajanja);

                                    // azuriranje brojaca da se lakse vidi da li su svi artikli stigli u grad spajanja
                                    brojArtikala++;
                                    if (idGraArt == gradSpajanja) {
                                        brojArtikalaUGraduSpajanja++;
                                    }
                                    if (putanja == null) {
                                        continue;
                                    }

                                    String sqlAzurirajSadrzi = "update Sadrzi set BrDana = ? , IdGra = ? where IdSad = ? ";
                                    try (PreparedStatement psAzurirajSadrzi = connection.prepareStatement(sqlAzurirajSadrzi)) {
                                        if (Integer.parseInt(putanja.split("-")[0]) == brDana + 1) { // menja se grad
                                            String[] gradovi = putanja.split("-")[2].split(",");
                                            int idGraNov = Integer.parseInt(gradovi[1]); // prvi grad u putanji ce biti trenutni grad artikla
                                            psAzurirajSadrzi.setInt(1, 0);
                                            psAzurirajSadrzi.setInt(2, idGraNov);
                                            if (idGraNov == gradSpajanja) {
                                                brojArtikalaUGraduSpajanja++;
                                            }
                                        }
                                        else { // artikal treba da ostane u gradu i dalje jer nije proslo dovoljno vremena
                                            psAzurirajSadrzi.setInt(1, brDana + 1);
                                            psAzurirajSadrzi.setInt(2, idGraArt);
                                        }
                                        psAzurirajSadrzi.setInt(3, idSad);
                                        psAzurirajSadrzi.executeUpdate();
                                    }

                                }
                            }
                        }

                        String sqlAzurirajStanjePorudzbine = "update Porudzbina set Stanje = ?, VremePrijema = ? where IdPor = ? ";
                        try (PreparedStatement psAzurirajStanjePorudzbine = connection.prepareStatement(sqlAzurirajStanjePorudzbine)) {

                            // ako su svi artikli u gradu spajanja i to je ujedno grad kupca
                            if (brojArtikala == brojArtikalaUGraduSpajanja && gradSpajanja == idGraKupca) {
                                psAzurirajStanjePorudzbine.setString(1, "arrived");
                                psAzurirajStanjePorudzbine.setTimestamp(2, new Timestamp(this.getCurrentTime().getTimeInMillis()));
                            }
                            // stigli su u grad spajanja, sad treba da idu do grada kupca
                            else if (brojArtikala == brojArtikalaUGraduSpajanja) {
                                psAzurirajStanjePorudzbine.setString(1, "assembled");
                                psAzurirajStanjePorudzbine.setNull(2, java.sql.Types.TIMESTAMP);
                            }
                            else {
                                continue;
                            }
                            psAzurirajStanjePorudzbine.setInt(3, idPor);
                            psAzurirajStanjePorudzbine.executeUpdate();
                        }
                    }
                    else if (stanje.equals("assembled")) { // ako je vec spojena, narudzbina, onda se mesta svih artikala azuriraju odjednom
                        String sqlSadrzi = "select IdGra, IdArt, BrDana, IdSad from Sadrzi where IdPor = ? ";
                        int brojArtikala = 0;
                        int brojArtikalaUGraduSpajanja = 0;
                        try (PreparedStatement psSadrzi = connection.prepareStatement(sqlSadrzi)) {
                            psSadrzi.setInt(1, idPor);
                            try (ResultSet rsSadrzi = psSadrzi.executeQuery()) {
                                while (rsSadrzi.next()) {
                                    int idGraArt = rsSadrzi.getInt(1);
                                    int idArt = rsSadrzi.getInt(2);
                                    int brDana = rsSadrzi.getInt(3);
                                    int idSad = rsSadrzi.getInt(4);
                                    String putanja = najkracaPutanja(idGraArt, idGraKupca);
                                    if (putanja == null) {
                                        continue;
                                    }

                                    String sqlAzurirajSadrzi = "update Sadrzi set BrDana = ? , IdGra = ? where IdPor = ? ";
                                    try (PreparedStatement psAzurirajSadrzi = connection.prepareStatement(sqlAzurirajSadrzi)) {
                                        if (Integer.parseInt(putanja.split("-")[0]) == brDana + 1) { // menja se grad
                                            String[] gradovi = putanja.split("-")[2].split(",");
                                            int idGraNov = Integer.parseInt(gradovi[1]); // prvi grad u putanji ce biti trenutni grad artikla
                                            psAzurirajSadrzi.setInt(1, 0);
                                            psAzurirajSadrzi.setInt(2, idGraNov);

                                            // provera da li su artikli stigli do kupca
                                            if (idGraNov == idGraKupca) {
                                                String sqlAzurirajStanjePorudzbine = "update Porudzbina set Stanje = ?, VremePrijema = ? where IdPor = ? ";
                                                try (PreparedStatement psAzurirajStanjePorudzbine = connection.prepareStatement(sqlAzurirajStanjePorudzbine)) {
                                                    psAzurirajStanjePorudzbine.setString(1, "arrived");
                                                    psAzurirajStanjePorudzbine.setTimestamp(2, new Timestamp(this.getCurrentTime().getTimeInMillis()));
                                                    psAzurirajStanjePorudzbine.setInt(3, idPor);
                                                    psAzurirajStanjePorudzbine.executeUpdate();
                                                }
                                            }
                                        }

                                        // samo se azurira broj dana
                                        else {
                                            psAzurirajSadrzi.setInt(1, brDana + 1);
                                            psAzurirajSadrzi.setInt(2, idGraArt);
                                        }
                                        psAzurirajSadrzi.setInt(3, idPor);
                                        psAzurirajSadrzi.executeUpdate();
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            catch (SQLException ex) {
                Logger.getLogger(uz200073_GeneralOperations.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
        return calendar;
    }

    @Override
    public Calendar getCurrentTime() {
        Calendar c = Calendar.getInstance();
        c.setTimeInMillis(calendar.getTimeInMillis());
        return c;
    }

    @Override
    public void eraseAll() {
        try (Statement stmt = connection.createStatement()) {
            stmt.execute("delete from Oglasava");
            stmt.execute("delete from Sadrzi");
            stmt.execute("delete from Linija");
            stmt.execute("delete from Transakcija");
            stmt.execute("delete from Prodavnica");
            stmt.execute("delete from Artikal");
            stmt.execute("delete from Porudzbina");
            stmt.execute("delete from Kupac");
            stmt.execute("delete from Grad");
            stmt.execute("delete from Sistem");
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_GeneralOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

}
