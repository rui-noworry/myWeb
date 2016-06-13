package dkt.teacher.view.dialog;



import java.util.Calendar;

import dkt.teacher.R;
import dkt.teacher.util.DateUtil;
import dkt.teacher.view.calendar.CalendarWidget;
import dkt.teacher.view.calendar.OnCalendarSelectedListenter;
import dkt.teacher.view.time.NumericWheelAdapter;
import dkt.teacher.view.time.OnWheelChangedListener;
import dkt.teacher.view.time.OnWheelScrollListener;
import dkt.teacher.view.time.WheelView;
import android.app.Dialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.animation.AnticipateOvershootInterpolator;
import android.widget.TextView;

public class CanLendarTimeDialog implements OnCalendarSelectedListenter{
	Context context;
	WheelView hourWheel;
	WheelView minuteWheel;
	CalendarWidget calendarWidget;
	TextView timeTitle;
	private TimeListener listener;
    int year ;
    int month;
    int day;
    int hour;
    int minute;
	public TimeListener getListener() {
		return listener;
	}

	public void setListener(TimeListener listener) {
		this.listener = listener;
	}

	public CanLendarTimeDialog(Context context) {
		this.context = context;
	}

	// Wheel scrolled flag
	private boolean wheelScrolled = false;

	public void createResourceDialog() {
		LayoutInflater inflater = (LayoutInflater) context
				.getSystemService(context.LAYOUT_INFLATER_SERVICE);
		View layout = inflater.inflate(R.layout.calendar_dialog, null);
		final Dialog dialog = new Dialog(context, R.style.dialog);
		dialog.setContentView(layout);
		dialog.setCancelable(true);
		dialog.show();

		timeTitle= (TextView) layout.findViewById(R.id.titleTime);
		
		hourWheel = (WheelView) layout.findViewById(R.id.hour_pick);
		minuteWheel = (WheelView) layout.findViewById(R.id.minute_pick);
		initView(hourWheel, 23);
		initView(minuteWheel, 59);
		
		calendarWidget = (CalendarWidget) layout.findViewById(R.id.calenaract_calendar_widget);
		calendarWidget.setCalendarSelectedListenter(this);
		
		initCalendar();
		
		layout.findViewById(R.id.ok).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				long time = getSelectLong();
				listener.onTimeSelect(time);
				dialog.dismiss();
				
			}
		});
		
		layout.findViewById(R.id.cancel).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				dialog.dismiss();
			}
		});

	}
	
	private long getSelectLong(){
		long time = 0l;
		String temp ="";
		StringBuffer sb = new StringBuffer();
		
		sb.append(year+"-");
		temp = addZero(month);
		sb.append(temp+"-");
		temp = addZero(day);
		sb.append(temp+" ");
		
		temp = addZero(hour);
		sb.append(temp+":");   
		temp = addZero(minute);
		sb.append(temp+":");
		sb.append("00");
		time = DateUtil.stringToDate(sb.toString()).getTime();
		
		return time;
	}
	
	
	private String addZero(int temp){
		String result = "";
		if(temp < 10){
			result = "0"+temp;
		}else{
			result = ""+temp;
		}
		return result;
	}  
	
	private void initCalendar(){
		Calendar today = Calendar.getInstance();
		year  =today.get(Calendar.YEAR);
		month = today.get(Calendar.MONTH)+1;
		day = today.get(Calendar.DAY_OF_MONTH);
		hour  = 8;
		minute = 0;  
		timeTitle.setText(year+"年"+(month)+"月"+day+"日" +hour+"点"+minute+"分");
	}

	private void initView(WheelView wheel, int index) {
		wheel.setAdapter(new NumericWheelAdapter(0, index));
		if(index > 24){
			wheel.setCurrentItem(0);
			
		}else{
			wheel.setCurrentItem(8);
		}
		wheel.addChangingListener(changedListener);
		wheel.addScrollingListener(scrolledListener);
		wheel.setCyclic(true);
		wheel.setInterpolator(new AnticipateOvershootInterpolator());
		
	}

	private OnWheelChangedListener changedListener = new OnWheelChangedListener() {
		public void onChanged(WheelView wheel, int oldValue, int newValue) {
			if (!wheelScrolled) {
				updateStatus();
			}
		}
	};

	private OnWheelScrollListener scrolledListener = new OnWheelScrollListener() {
		public void onScrollingStarted(WheelView wheel) {
			wheelScrolled = true;
		}

		public void onScrollingFinished(WheelView wheel) {
			wheelScrolled = false;
			updateStatus();
		}
	};

	private void updateStatus() {

		hour = hourWheel.getCurrentItem();
		minute =minuteWheel.getCurrentItem();
		timeTitle.setText(year+"年"+(month)+"月"+day+"日" +hour+"点"+minute+"分");
		
	}

	@Override
	public void onSelected(int year, int month, int day) {
		hour = hourWheel.getCurrentItem();
		minute =minuteWheel.getCurrentItem();
		this.year = year;
		this.month = month+1;
		this.day = day;
		timeTitle.setText(year+"年"+(month+1)+"月"+day+"日" +hour+"点"+minute+"分");
	
	}
	
	public interface TimeListener{
		public void onTimeSelect(long time);
	}
	
	

}
