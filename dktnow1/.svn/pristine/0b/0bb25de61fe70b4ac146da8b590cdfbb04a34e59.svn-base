package dkt.student.util.bitmap.download;

import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.FilterInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

import android.util.Log;


public class SimpleHttpDownloader implements Downloader{
	
	private static final String TAG = "BitmapDownloader";
	
	private static final int IO_BUFFER_SIZE = 8 * 1024; //8k
	
	/**
	 * 把网络图片转下载到文件的 outputStream
	 * @param urlString
	 * @param outputStream
	 * @return
	 */
	 public boolean downloadToLocalStreamByUrl(String urlString, OutputStream outputStream) {
	        HttpURLConnection urlConnection = null;
	        BufferedOutputStream out = null;
	        FlushedInputStream in = null;

	        try {
	            final URL url = new URL(urlString);
	            urlConnection = (HttpURLConnection) url.openConnection();
	            in = new FlushedInputStream(new BufferedInputStream(urlConnection.getInputStream(), IO_BUFFER_SIZE));
	            out = new BufferedOutputStream(outputStream, IO_BUFFER_SIZE);

	            int b;
	            while ((b = in.read()) != -1) {
	                out.write(b);
	            }
	            return true;
	        } catch (final IOException e) {
	            Log.e(TAG, "Error in downloadBitmap - " + e);
	        } finally {
	            if (urlConnection != null) {
	                urlConnection.disconnect();
	            }
	            try {
	                if (out != null) {
	                    out.close();
	                }
	                if (in != null) {
	                    in.close();
	                }
	            } catch (final IOException e) {}
	        }
	        return false;
	    }

	    
	    public class FlushedInputStream extends FilterInputStream {

	    	public FlushedInputStream(InputStream inputStream) {
	    		super(inputStream);
	    	}

	    	@Override
	    	public long skip(long n) throws IOException {
	    		long totalBytesSkipped = 0L;
	    		while (totalBytesSkipped < n) {
	    			long bytesSkipped = in.skip(n - totalBytesSkipped);
	    			if (bytesSkipped == 0L) {
	    				int by_te = read();
	    				if (by_te < 0) {
	    					break; // we reached EOF
	    				} else {
	    					bytesSkipped = 1; // we read one byte
	    				}
	    			}
	    			totalBytesSkipped += bytesSkipped;
	    		}
	    		return totalBytesSkipped;
	    	}
	    }
}
