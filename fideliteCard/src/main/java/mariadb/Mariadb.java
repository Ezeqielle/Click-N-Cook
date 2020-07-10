package mariadb;

import java.sql.*;
import java.util.*;
import java.util.Scanner;

public class Mariadb {

    static final String JDBC_DRIVER = "org.mariadb.jdbc.Driver";
    static final String DB_URL = "jdbc:mariadb://ipserver/bdd";

    static final String USER = "user";
    static final String PASS = "password";

    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);
        Connection conn = null;
        Statement stmt = null;
        ResultSet rst = null;
        ResultSetMetaData rsmd = null;
        try{
            Class.forName("org.mariadb.jdbc.Driver");

            System.out.println("Connecting to database...");
            conn = DriverManager.getConnection("jdbc:mariadb://ipserver/bdd","user","password");
            System.out.println("Connected datavase successfully...");

            System.out.println("What user do you want ?");
            int str = sc.nextInt();
            stmt = conn.createStatement();

            rst = stmt.executeQuery("SELECT * FROM franchisee WHERE id="+str);
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
