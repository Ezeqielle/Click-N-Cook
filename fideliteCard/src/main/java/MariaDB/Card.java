package MariaDB;

import java.sql.*;
import java.util.Scanner;

public class Card {

    static final String JDBC_DRIVER = "org.mariadb.jdbc.Driver";
    static final String DB_URL = "jdbc:mariadb://localhost:3306/clickncook";

    static final String USER = "root";
    static final String PASS = "";

    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);
        Connection conn = null;
        Statement stmt = null;
        ResultSet rst;
        ResultSetMetaData rsmd;
        try{
            Class.forName(JDBC_DRIVER);

            System.out.println("Connecting to database...");
            conn = DriverManager.getConnection(DB_URL,USER,PASS);
            System.out.println("Connected database successfully...");

            System.out.println("What user do you want (enter the ID) ?");
            int str = sc.nextInt();
            stmt = conn.createStatement();

            rst = stmt.executeQuery("SELECT advantage FROM CLIENT WHERE id="+str);
            rsmd = rst.getMetaData();
            System.out.println("\n**********************************");
            for (int i = 1; i <= rsmd.getColumnCount(); i++){
                System.out.println("\t" + rsmd.getColumnName(i).toUpperCase() + "\t *");
            }
            System.out.println("\n**********************************");
            while (rst.next()){
                for (int i = 1; i <= rsmd.getColumnCount(); i++){
                    System.out.println("\t" + rst.getObject(i).toString() + "\t |");
                }
                System.out.println("\n---------------------------------");
            }
        }catch (SQLException se){
            se.printStackTrace();
        }catch (Exception e){
            e.printStackTrace();
        }finally {
            try{
                if (stmt != null){
                    conn.close();
                }
            }catch (SQLException se){
            }
            try {
                if (conn != null){
                    conn.close();
                }
            }catch (SQLException se){
                se.printStackTrace();
            }
        }
        System.out.println("Goodbye!");
    }
}

