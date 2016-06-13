package dkt.teacher.util;

public class StringUtil {

	/**
	 * 判断值 是否为空
	 * 为空返回true
	 * 不为空返回false
	 * @param params
	 * @return
	 */
	public static boolean checkIsNull(String[] params) {
		boolean result = false;
		int count = params.length;
		for (int i = 0; i < count; i++) {
			if (params[i] == null || params[i].equals("") || params[i].equals("null")) {
				result = true;
				break;
			}
		}
		return result;
	}
	
	
}
