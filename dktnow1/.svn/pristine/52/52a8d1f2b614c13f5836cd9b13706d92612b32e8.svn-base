package dkt.teacher.util;

import java.util.HashMap;

public class MediaFileUtil {
	public HashMap<String, String> defaultType;

	public MediaFileUtil() {
		defaultType = new HashMap<String, String>();
		loadDefaultMineType();
	}

	private void loadDefaultMineType() {
		// 文档
		defaultType.put("html", "0");
		defaultType.put("doc", "0");
		defaultType.put("pdf", "0");
		defaultType.put("docx", "0");
		defaultType.put("txt", "0");
		defaultType.put("ppt", "0");
		defaultType.put("pptx", "0");
		defaultType.put("txt", "0");
		defaultType.put("xls", "0");
		defaultType.put("xlsx", "0");
		// 图片
		defaultType.put("png", "1");
		defaultType.put("jpg", "1");
		defaultType.put("jpeg", "1");
		defaultType.put("gif", "1");
		defaultType.put("bmp", "1");
		// 视频
		defaultType.put("avi", "2");
		defaultType.put("flv", "2");
		defaultType.put("f4v", "2");
		defaultType.put("mpg", "2");
		defaultType.put("mp4", "2");
		defaultType.put("rmvb", "2");
		defaultType.put("rm", "2");
		defaultType.put("mkv", "2");
		defaultType.put("vob", "2");
		defaultType.put("ts", "2");
		defaultType.put("m2ts", "2");
		defaultType.put("m2p", "2");
		defaultType.put("wmv", "2");
		defaultType.put("asf", "2");
		defaultType.put("d2v", "2");
		defaultType.put("ogm", "2");
		defaultType.put("3gp", "2");
		defaultType.put("divx", "2");
		defaultType.put("mpeg", "2");
		defaultType.put("m4v", "2");
		defaultType.put("mov", "2");
		defaultType.put("tp", "2");
		defaultType.put("iso", "2");
		defaultType.put("rt", "2");
		defaultType.put("qt", "2");
		defaultType.put("ram", "2");
		defaultType.put("vod", "2");
		//defaultType.put("dat", "2");
		// 音频
		defaultType.put("mp3", "3");
		defaultType.put("wav", "3");
		defaultType.put("ogg", "3");
		defaultType.put("wma", "3");
		defaultType.put("wave", "3");
		defaultType.put("midi", "3");
		defaultType.put("mp2", "3");
		defaultType.put("aac", "3");
		defaultType.put("amr", "3");
		defaultType.put("ape", "3");
		defaultType.put("flac", "3");
		defaultType.put("m4a", "3");
		//原笔迹
		defaultType.put("db", "4");
	}


	/**
	 * 得到后缀名
	 */
	private  String getSuffix(String name) {
		name = name.substring(name.lastIndexOf(".") + 1);
		return name;
	}

	/**
	 * 判断多媒体类型 根据键获得 键值
	 * 
	 * @param str
	 * @return
	 */
	public  int getMineType(String str) {
		/**
		 * 判断map中是否含有这个值
		 */
		if (defaultType.containsValue(str)) {
			int type = Integer.valueOf(defaultType.get(str));
			return type;
		} else {
			return -1;
		}
	}
	
	

}
