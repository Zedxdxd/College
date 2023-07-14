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
import java.util.Calendar;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import rs.etf.sab.operations.TransactionOperations;

/**
 *
 * @author zeljk
 */
public class uz200073_TransactionOperations implements TransactionOperations {

    private Connection connection = DB.getInstance().getConnection();

    @Override
    public BigDecimal getBuyerTransactionsAmmount(int buyerId) {
        String sqlSuma = "select sum(Novac) from Transakcija where IdKup = ? ";
        try (PreparedStatement psSuma = connection.prepareStatement(sqlSuma)) {
            psSuma.setInt(1, buyerId);
            try (ResultSet rsSuma = psSuma.executeQuery()) {
                if (rsSuma.next()) {
                    return rsSuma.getBigDecimal(1) == null ? null : rsSuma.getBigDecimal(1);
                }
                else {
                    return new BigDecimal(-1);
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new BigDecimal(-1);
    }

    @Override
    public BigDecimal getShopTransactionsAmmount(int shopId) {
        String sqlSuma = "select sum(Novac) from Transakcija where IdPro = ? ";
        try (PreparedStatement psSuma = connection.prepareStatement(sqlSuma)) {
            psSuma.setInt(1, shopId);
            try (ResultSet rsSuma = psSuma.executeQuery()) {
                if (rsSuma.next()) {
                    return rsSuma.getBigDecimal(1) == null ? new BigDecimal(0).setScale(3) : rsSuma.getBigDecimal(1).setScale(3);
                }
                else {
                    return new BigDecimal(-1);
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new BigDecimal(-1);
    }

    @Override
    public List<Integer> getTransationsForBuyer(int buyerId) {
        List<Integer> transakcije = new ArrayList<>();
        String sqlTransakcije = "select IdTra from Transakcija where IdKup = ? ";
        try (PreparedStatement psTransakcije = connection.prepareStatement(sqlTransakcije)){
            psTransakcije.setInt(1, buyerId);
            try (ResultSet rsTransakcije = psTransakcije.executeQuery()){
                while (rsTransakcije.next()){
                    transakcije.add(rsTransakcije.getInt(1));
                }
                return transakcije;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public int getTransactionForBuyersOrder(int orderId) {
        String sqlTransakcija = "select IdTra from Transakcija where IdPor = ? and IdPro is null ";
        try (PreparedStatement psTransakcija = connection.prepareStatement(sqlTransakcija)){
            psTransakcija.setInt(1, orderId);
            try(ResultSet rsTransakcija = psTransakcija.executeQuery()){
                if (rsTransakcija.next()){
                    return rsTransakcija.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public int getTransactionForShopAndOrder(int orderId, int shopId) {
        String sqlTransakcija = "select IdTra from Transakcija where IdPor = ? and IdPro = ? and IdKup is null ";
        try (PreparedStatement psTransakcija = connection.prepareStatement(sqlTransakcija)){
            psTransakcija.setInt(1, orderId);
            psTransakcija.setInt(2, shopId);
            try(ResultSet rsTransakcija = psTransakcija.executeQuery()){
                if (rsTransakcija.next()){
                    return rsTransakcija.getInt(1);
                }
                else {
                    return -1;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
            return -1;
        }
    }

    @Override
    public List<Integer> getTransationsForShop(int shopId) {
        List<Integer> transakcije = new ArrayList<>();
        String sqlTransakcije = "select IdTra from Transakcija where IdPro = ? ";
        try (PreparedStatement psTransakcije = connection.prepareStatement(sqlTransakcije)){
            psTransakcije.setInt(1, shopId);
            try (ResultSet rsTransakcije = psTransakcije.executeQuery()){
                while (rsTransakcije.next()){
                    transakcije.add(rsTransakcije.getInt(1));
                }
                if (transakcije.isEmpty()){
                    return null;
                }
                return transakcije;
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public Calendar getTimeOfExecution(int transactionId) {
        String sqlDatum = "select Datum from Transakcija where IdTra = ? ";
        try (PreparedStatement psDatum = connection.prepareStatement(sqlDatum)){
            psDatum.setInt(1, transactionId);
            try (ResultSet rsDatum = psDatum.executeQuery()){
                if (rsDatum.next()){
                    Calendar c = Calendar.getInstance();
                    c.setTimeInMillis(rsDatum.getTimestamp(1).getTime());
                    return c;
                }
                else {
                    return null;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public BigDecimal getAmmountThatBuyerPayedForOrder(int orderId) {
        String sqlNovac = "select Novac from Transakcija where IdPor = ? and IdPro is null ";
        try (PreparedStatement psNovac = connection.prepareStatement(sqlNovac)){
            psNovac.setInt(1, orderId);
            try (ResultSet rsNovac = psNovac.executeQuery()){
                if (rsNovac.next()){
                    return rsNovac.getBigDecimal(1);
                }
                else {
                    return null;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public BigDecimal getAmmountThatShopRecievedForOrder(int shopId, int orderId) {
        String sqlNovac = "select Novac from Transakcija where IdPro = ? and IdPor ? ";
        try (PreparedStatement psNovac = connection.prepareStatement(sqlNovac)){
            psNovac.setInt(1, shopId);
            psNovac.setInt(2, orderId);
            try (ResultSet rsNovac = psNovac.executeQuery()){
                if (rsNovac.next()){
                    return rsNovac.getBigDecimal(1);
                }
                else {
                    return null;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public BigDecimal getTransactionAmount(int transactionId) {
        String sqlNovac = "select Novac from Transakcija where IdTra = ? ";
        try (PreparedStatement psNovac = connection.prepareStatement(sqlNovac)){
            psNovac.setInt(1, transactionId);
            try (ResultSet rsNovac = psNovac.executeQuery()){
                if (rsNovac.next()){
                    return rsNovac.getBigDecimal(1);
                }
                else {
                    return null;
                }
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    @Override
    public BigDecimal getSystemProfit() {
        String sqlProfit = "select Profit from Sistem";
        try (PreparedStatement psProfit = connection.prepareStatement(sqlProfit);
                ResultSet rsProfit = psProfit.executeQuery()){
            if (rsProfit.next()){
                return rsProfit.getBigDecimal(1);
            }
            else {
                return new BigDecimal(0).setScale(3);
            }
        }
        catch (SQLException ex) {
            Logger.getLogger(uz200073_TransactionOperations.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

}
