package dkt.student.util;

import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;


public class Md5Util {

    
    public static HashMap<String,Object> testMD5Map(HashMap<String,Object> map){
    	
    	Iterator it = map.entrySet().iterator();
		List<String> list= new ArrayList<String>();
		while(it.hasNext()){
			Map.Entry m = (Entry) it.next();
			list.add(m.getKey()+"");
		}
		int count = list.size();
		//取值
		String[] temp = new String[count];
		list.toArray(temp);
		//排序
		Arrays.sort(temp);
		//组合
		StringBuffer sb = new StringBuffer();
    	for (int i = 0; i < count; i++ ) {
    		String s1 = URLEncoder.encode(temp[i]);
    		if (i != 0) {sb.append("&");}
    		sb.append(s1+"="+map.get(temp[i]));
    	}
    	String result = new MD5(sb.toString()).compute();
    	System.out.println("_______MD5开始____________");
    	System.out.println(sb.toString());
    	System.out.println("________MD5结束____________");
    	map.put("sign", result);
    	return map;
		
    }
    
}
