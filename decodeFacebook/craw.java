
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.FileOutputStream;
import java.net.HttpURLConnection;

//for login
import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;

import org.apache.http.Header;
import org.apache.http.HttpEntity;
import org.apache.http.HttpHeaders;
import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;

@SuppressWarnings("deprecation")



public class craw {
    public static void main(String[] args) throws IOException {
        String path = "https://www.facebook.com/profile.php?id=100005185755133&sk=about";
        URL name = new URL(path);
        URLConnection conn = name.openConnection();
        InputStream input = conn.getInputStream();
        InputStreamReader isr = new InputStreamReader(input);
        BufferedReader buf = new BufferedReader(isr);
        //  String inputLine=null;

        // File file=new File("Tianyuan Chen.html");
        // BufferedReader buf=new BufferedReader(new InputStreamReader(new FileInputStream(file)));
        String str=null;
        // String regex="(.)+@(.)+(\\.[a-z]+){1,}";
        // Pattern pattern=Pattern.compile(regex);
        while((str=buf.readLine())!=null)
        {
            System.out.println(str);
            //  Matcher matcher=pattern.matcher(str);
            //  while(matcher.find())
            //   System.out.println(matcher.group());
            String searchPart = "<span dir=\"ltr\">";
           // String searchPart = "9482";
         //   System.out.println(searchPart);
            int retVal = str.indexOf(searchPart,0);
            if(retVal!=-1){
                int phoneNumberEndMark = 30;
                while(((int)str.charAt(phoneNumberEndMark)>=48 && (int)str.charAt(phoneNumberEndMark)<=57)){
                    phoneNumberEndMark++;
                }
                System.out.println(str.substring(retVal+16,retVal+phoneNumberEndMark));
            }
            
        }
        buf.close();
        
    }
}