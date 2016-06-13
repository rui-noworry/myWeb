package dkt.student.util;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

public class FinalFilUtil {
	/**
	 * 根据string 查看文件是否存在 不存在则创建
	 */
	public static boolean isFileExists(File file) {
		boolean isExist = false;
		if (!file.exists()) {
			isExist = file.mkdir();
		} else {
			isExist = true;
		}
		return isExist;
	}

	/**
	 * 根据string 查看文件是否存在并删除文件 不存在则创建
	 */
	public static boolean deleteFileExists(File file) {
		boolean isExist = false;
		if (!file.exists()) {
			isExist = file.delete();
		} else {
			isExist = true;
		}
		return isExist;
	}

	/**
	 * 删除map所含有的文件,file为文件夹路径
	 * 
	 * @param map
	 */
	public static void deleteFileFromMap(Map<String, Object> map, File file) {
		// 删除多余的文件
		Iterator it = map.entrySet().iterator();
		List<String> list = new ArrayList<String>();
		while (it.hasNext()) {
			Map.Entry m = (Entry) it.next();
			String deleteFileName = m.getKey() + "";
			FinalFilUtil.deleteFileExists(new File(file.getPath()
					+ deleteFileName));
		}
	}
	
	
	public static Map<String, Object>  getFileNameMapFromFile(File file){
		File[] tempFiles = file.listFiles();
		// 存放本地文件名
		Map<String, Object> map = new HashMap<String, Object>();
		for (File tempFile : tempFiles) {
			if (!tempFile.isDirectory()) {
				map.put(tempFile.getName(), "");
			}
		}
		
		return map;
	}

}
