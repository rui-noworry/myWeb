package dkt.teacher.socket;

import java.util.HashMap;
import java.util.Map;

public class Header implements IHeader {
	private Map<String, String> data = null;

	public Header() {

	}

	public Header(byte[] data) throws Exception {
		// this(CipherUtil.decrypt(new String(data, HEADER_ENCODING_CHARSET)));
		this(new String(data, HEADER_ENCODING_CHARSET));
	}





	public Header(String headerString) {

		if (headerString != null) {
			data = new HashMap<String, String>();
			String[] headerArray = headerString.trim().split("\n");

			for (String headerPair : headerArray) {
				if(headerPair.equals("")){
					continue;
				}
				int firstEqIndex = headerPair.indexOf(":");

				if (firstEqIndex == -1)
					throw new java.lang.RuntimeException("非法的头信息");

				String name = headerPair.substring(0, firstEqIndex);
				String value = headerPair.substring(firstEqIndex + 1);

				data.put(name, value);
			}
		}
	}

	public String getHeader(String name) {

		return null!=data?data.get(name):null;
	}
	@Override
	public int getResponseCode() {
		String value=getHeader(HEAD_STATUS_KEY);
		try{
			return Integer.parseInt(value);
		}catch(Exception e){
			
		}
		return 0;
	}
	public int getContentLength() {
		String value=getHeader(HEAD_CONTENT_LENGTH_KEY);
		try{
			return Integer.parseInt(value);
		}catch(Exception e){
			
		}
		return 0;
	}

	public void addHeader(String name, String value) throws Exception {
		if(null==data){
			data = new HashMap<String, String>();
		}
		if (null != name && null!=value){
			if(HEAD_AUTH_USER_KEY.equalsIgnoreCase(name) || HEAD_AUTH_PWD_KEY.equalsIgnoreCase(name)){
				//加密 数据
				data.put(name.toLowerCase(), value);
			}else{
				data.put(name.toLowerCase(), value);
			}
		}
			
	}

	public String toString() {
		if (null == data)
			return "";
		StringBuffer hreader = new StringBuffer();

		for (String name : data.keySet()) {
			hreader.append(name).append(":").append(data.get(name))
					.append("\n");

		}
		return hreader.toString();
	}

	public Map<String, String> getHeaders() {
		return data;
	}




	public void destroy() {
		if(null!=data){
			data.clear();
			this.data=null;
		}
		
	}

	
}
