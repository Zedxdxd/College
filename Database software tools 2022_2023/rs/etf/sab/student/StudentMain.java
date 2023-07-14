
import java.math.BigDecimal;
import rs.etf.sab.operations.*;
import org.junit.Test;
import rs.etf.sab.student.*;
import rs.etf.sab.tests.TestHandler;
import rs.etf.sab.tests.TestRunner;

import java.util.Calendar;
import java.util.List;

public class StudentMain {

    public static void main(String[] args) {
        
        // ako se slucajno menja popust prodavnice prilikom transporta onda treba
        // da se u tabelu sadrzi upise popust pri porudzbini
        // onda u izracunavanju transakcija kod prodavnca da se uzme taj popust a ne iz prodavnice pretty simple right?

        ArticleOperations articleOperations = new uz200073_ArticleOperations(); // Change this for your implementation (points will be negative if interfaces are not implemented).
        BuyerOperations buyerOperations = new uz200073_BuyerOperations();
        CityOperations cityOperations = new uz200073_CityOperations();
        GeneralOperations generalOperations = new uz200073_GeneralOperations();
        OrderOperations orderOperations = new uz200073_OrderOperations(generalOperations);
        ShopOperations shopOperations = new uz200073_ShopOperations();
        TransactionOperations transactionOperations = new uz200073_TransactionOperations();

        TestHandler.createInstance(
                articleOperations,
                buyerOperations,
                cityOperations,
                generalOperations,
                orderOperations,
                shopOperations,
                transactionOperations
        );

        TestRunner.runTests(); 
        /* generalOperations.eraseAll();
        final Calendar initialTime = Calendar.getInstance();
        initialTime.clear();
        initialTime.set(2018, 0, 1);
        generalOperations.setInitialTime(initialTime);
        final Calendar receivedTime = Calendar.getInstance();
        receivedTime.clear();
        receivedTime.set(2018, 0, 22);
        final int cityB = cityOperations.createCity("B");
        final int cityC1 = cityOperations.createCity("C1");
        final int cityA = cityOperations.createCity("A");
        final int cityC2 = cityOperations.createCity("C2");
        final int cityC3 = cityOperations.createCity("C3");
        final int cityC4 = cityOperations.createCity("C4");
        final int cityC5 = cityOperations.createCity("C5");
        cityOperations.connectCities(cityB, cityC1, 8);
        cityOperations.connectCities(cityC1, cityA, 10);
        cityOperations.connectCities(cityA, cityC2, 3);
        cityOperations.connectCities(cityC2, cityC3, 2);
        cityOperations.connectCities(cityC3, cityC4, 1);
        cityOperations.connectCities(cityC4, cityA, 3);
        cityOperations.connectCities(cityA, cityC5, 15);
        cityOperations.connectCities(cityC5, cityB, 2);
        final int shopA = shopOperations.createShop("shopA", "A");
        final int shopC2 = shopOperations.createShop("shopC2", "C2");
        final int shopC3 = shopOperations.createShop("shopC3", "C3");
        shopOperations.setDiscount(shopA, 20);
        shopOperations.setDiscount(shopC2, 50);
        final int laptop = articleOperations.createArticle(shopA, "laptop", 1000);
        final int monitor = articleOperations.createArticle(shopC2, "monitor", 200);
        final int stolica = articleOperations.createArticle(shopC3, "stolica", 100);
        final int sto = articleOperations.createArticle(shopC3, "sto", 200);
        shopOperations.increaseArticleCount(laptop, 10);
        shopOperations.increaseArticleCount(monitor, 10);
        shopOperations.increaseArticleCount(stolica, 10);
        shopOperations.increaseArticleCount(sto, 10);
        final int buyer = buyerOperations.createBuyer("kupac", cityB);
        buyerOperations.increaseCredit(buyer, new BigDecimal("20000"));
        final int order = buyerOperations.createOrder(buyer);
        orderOperations.addArticle(order, laptop, 5);
        orderOperations.addArticle(order, monitor, 4);
        orderOperations.addArticle(order, stolica, 10);
        orderOperations.addArticle(order, sto, 4);
        if (null == orderOperations.getSentTime(order)) {
            System.out.println("yeah");
        }
        if ("created".equals(orderOperations.getState(order))) {
            System.out.println("yeah1");
        }
        orderOperations.completeOrder(order);
        if ("sent".equals(orderOperations.getState(order))) {
            System.out.println("yeah2");
        }
        final int buyerTransactionId = transactionOperations.getTransationsForBuyer(buyer).get(0);
        if (((Object) initialTime).equals((Object) transactionOperations.getTimeOfExecution(buyerTransactionId))) {
            System.out.println("yeah3");
        }
        if (transactionOperations.getTransationsForShop(shopA) == null) {
            System.out.println("yeah4");
        }
        final BigDecimal shopAAmount = new BigDecimal("5").multiply(new BigDecimal("1000")).setScale(3);
        final BigDecimal shopAAmountWithDiscount = new BigDecimal("0.8").multiply(shopAAmount).setScale(3);
        final BigDecimal shopC2Amount = new BigDecimal("4").multiply(new BigDecimal("200")).setScale(3);
        final BigDecimal shopC2AmountWithDiscount = new BigDecimal("0.5").multiply(shopC2Amount).setScale(3);
        final BigDecimal shopC3AmountWithDiscount;
        final BigDecimal shopC3Amount = shopC3AmountWithDiscount = new BigDecimal("10").multiply(new BigDecimal("100")).add(new BigDecimal("4").multiply(new BigDecimal("200"))).setScale(3);
        final BigDecimal amountWithoutDiscounts = shopAAmount.add(shopC2Amount).add(shopC3Amount).setScale(3);
        final BigDecimal amountWithDiscounts = shopAAmountWithDiscount.add(shopC2AmountWithDiscount).add(shopC3AmountWithDiscount).setScale(3);
        final BigDecimal systemProfit = amountWithDiscounts.multiply(new BigDecimal("0.05")).setScale(3);
        final BigDecimal shopAAmountReal = shopAAmountWithDiscount.multiply(new BigDecimal("0.95")).setScale(3);
        final BigDecimal shopC2AmountReal = shopC2AmountWithDiscount.multiply(new BigDecimal("0.95")).setScale(3);
        final BigDecimal shopC3AmountReal = shopC3AmountWithDiscount.multiply(new BigDecimal("0.95")).setScale(3);
        if (((Object) amountWithDiscounts).equals((Object) orderOperations.getFinalPrice(order))) {
            System.out.println("yeah5");
        }
        if (((Object) (amountWithoutDiscounts.subtract(amountWithDiscounts))).equals((Object) orderOperations.getDiscountSum(order))) {
            System.out.println("yeah6");
        }
        if (((Object) amountWithDiscounts).equals((Object) transactionOperations.getBuyerTransactionsAmmount(buyer))) {
            System.out.println("yeah7");
        }
        if (((Object) transactionOperations.getShopTransactionsAmmount(shopA)).equals((Object) new BigDecimal("0").setScale(3))) {
            System.out.println("yeah8");
        }
        if (((Object) transactionOperations.getShopTransactionsAmmount(shopC2)).equals((Object) new BigDecimal("0").setScale(3))) {
            System.out.println("yeah9");
        }
        if (((Object) transactionOperations.getShopTransactionsAmmount(shopC3)).equals((Object) new BigDecimal("0").setScale(3))) {
            System.out.println("yeah10");
        }
        if (((Object) new BigDecimal("0").setScale(3)).equals(transactionOperations.getSystemProfit())) {
            System.out.println("yeah11");
        }
        generalOperations.time(2);
        if (((Object) initialTime).equals((Object)orderOperations.getSentTime(order))) {
            System.out.println("yeah12");
        }
        if ((Object) orderOperations.getRecievedTime(order) == null) {
            System.out.println("yeah13");
        }
        if ((long)orderOperations.getLocation(order) == (long)cityA){
            System.out.println("yeah14");
        }
        generalOperations.time(9);
        if ((long)orderOperations.getLocation(order) == (long)cityA){
            System.out.println("yeah15");
        }
        generalOperations.time(8);
        if ((long)orderOperations.getLocation(order) == (long)cityC5){
            System.out.println("yeah16");
        }
        generalOperations.time(5);
        if ((long)orderOperations.getLocation(order) == (long)cityB){
            System.out.println("yeah17");
        }
        
        if (((Object)receivedTime).equals((Object)orderOperations.getRecievedTime(order))){
            System.out.println("yeah18");
        }
        if (((Object)shopAAmountReal).equals((Object)transactionOperations.getShopTransactionsAmmount(shopA))) {
            System.out.println("yeah19");
        }
        if (((Object)shopC2AmountReal).equals((Object)transactionOperations.getShopTransactionsAmmount(shopC2))) {
            System.out.println("yeah20");
        }
        if (((Object)shopC3AmountReal).equals((Object)transactionOperations.getShopTransactionsAmmount(shopC3))) {
            System.out.println("yeah21");
        }
        if (((Object)systemProfit).equals((Object)transactionOperations.getSystemProfit())){
            System.out.println("yeah22");
        }
        
        final int shopATransactionId = transactionOperations.getTransactionForShopAndOrder(order, shopA);
        if (shopATransactionId != -1){
            System.out.println("yeah23");
        }
        if (((Object)receivedTime).equals((Object)transactionOperations.getTimeOfExecution(shopATransactionId))){
            System.out.println("yeah24");
        }*/
    }
}
