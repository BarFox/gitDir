
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;



public class crawFile {
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