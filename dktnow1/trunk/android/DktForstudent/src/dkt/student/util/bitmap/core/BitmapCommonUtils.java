package dkt.student.util.bitmap.core;

import java.io.File;

import android.content.Context;
import android.graphics.Bitmap;
import android.os.Environment;
import android.os.StatFs;

public class BitmapCommonUtils {
	
	/**
	 * 获取可以使用的缓存目录
	 * @param context
	 * @param uniqueName 目录名称
	 * @return
	 */
    public static File getDiskCacheDir(Context context, String uniqueName) {
        final String cachePath = Environment.MEDIA_MOUNTED.equals(Environment.getExternalStorageState()) ? 
                		getExternalCacheDir(context).getPath() : context.getCacheDir().getPath();

        return new File(cachePath + File.separator + uniqueName);
    }

  

    /**
     * 获取bitmap的字节大小
     * @param bitmap
     * @return
     */
    public static int getBitmapSize(Bitmap bitmap) {
        return bitmap.getRowBytes() * bitmap.getHeight();
    }


   /**
    * 获取程序外部的缓存目录
    * @param context
    * @return
    */
    public static File getExternalCacheDir(Context context) {
        final String cacheDir = "/Android/data/" + context.getPackageName() + "/cache/";
        return new File(Environment.getExternalStorageDirectory().getPath() + cacheDir);
    }

    /**
     * 获取文件路径空间大小
     * @param path
     * @return
     */
    public static long getUsableSpace(File path) {
        final StatFs stats = new StatFs(path.getPath());
        return (long) stats.getBlockSize() * (long) stats.getAvailableBlocks();
    }

}
