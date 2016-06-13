package dkt.teacher.view.calendar;


import dkt.teacher.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Window;


public class CanlendarDialog extends Activity implements OnCalendarSelectedListenter
{
	private CalendarWidget calendarWidget;
	@Override
	protected void onCreate(Bundle savedInstanceState)
	{   
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_LEFT_ICON);
		setContentView(R.layout.calendar_dialog);
		getWindow().setFeatureDrawableResource(Window.FEATURE_LEFT_ICON, R.drawable.calendar);
		
		calendarWidget = (CalendarWidget)findViewById(R.id.calenaract_calendar_widget);
		calendarWidget.setCalendarSelectedListenter(this);
	}

	public void onSelected(int year, int month, int day)
	{
		Intent intent = new Intent();
		intent.putExtra("year", year);
		intent.putExtra("month", month);
		intent.putExtra("day", day);
		setResult(RESULT_OK,intent);
		finish();
	}
}
