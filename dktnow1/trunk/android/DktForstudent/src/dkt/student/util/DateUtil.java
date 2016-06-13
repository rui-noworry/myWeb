package dkt.student.util;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

/**
 * date 是一个中转 
 * @author Administrator
 *
 */
public class DateUtil {

	/**
	 * 
	 * @param str
	 * @return
	 */
	public static Date stringToDate(String dateStr){
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		Date date= null;
		try {
			date = sdf.parse(dateStr);
		} catch (ParseException e) {
			e.printStackTrace();
		}
		return date;

	}
	
	/**
	 * 
	 * @param date
	 * @return
	 */
	public static String dateToString(Date date){
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		String result = null;
		result = sdf.format(date);
		return result;
	}
	
	/**
	 * 
	 * @param date
	 * @return
	 */
	public static long dateToLong(Date date){
		return date.getTime();
	}
	/**
	 * 
	 * @param dateLong
	 * @return
	 */
	public static Date longToDate(long dateLong){
		Date date = new Date(dateLong);
		return date;
	}
	
	public static String longToStr(long dateLong){
		Date date = new Date(dateLong);
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		String result = null;
		result = sdf.format(date);
		return result;
	}
	
	
	

}
