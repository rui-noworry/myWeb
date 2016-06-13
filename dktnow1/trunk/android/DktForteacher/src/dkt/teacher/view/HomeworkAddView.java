package dkt.teacher.view;

import org.json.JSONArray;
import org.json.JSONException;

import android.content.Context;
import android.graphics.BitmapFactory;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import dkt.teacher.R;
import dkt.teacher.model.Homework;
import dkt.teacher.util.bitmap.FinalBitmap;

public class HomeworkAddView {

	private Context context;
	private FinalBitmap fb;

	public HomeworkAddView(Context context) {
		this.context = context;
		fb = new FinalBitmap(context).init();
	}
	
	/**
	 * 添加各种习题 单选1 多选2 填空3 判断4
	 * 
	 * @param linear
	 * @param map
	 */
	public void addExerciseView(LinearLayout linear, Homework exercise) {
		int style = Integer.parseInt(exercise.getToType());
		if (style == 1) {
			addSingleSelect(linear, exercise);
		} else if (style == 2) {
			addMultilSelect(linear, exercise);
		} else if (style == 4) {
			addJudgeSelect(linear, exercise);
		} else if (style == 3) {
			addFullSelect(linear, exercise);
		} else if(style == 5) {
			addShortAnswerSelect(linear, exercise);
		}
	}
	
	/**
	 * 增加单选
	 * 
	 * @param linear
	 * @param map
	 */
	private void addSingleSelect(final LinearLayout linear,
			final Homework map) {
		View view = LayoutInflater.from(context).inflate(
				R.layout.homework_single_selection, null);
		// image
		ImageView titleView = (ImageView) view
				.findViewById(R.id.exercise_title);
		
		byte[] myByte = map.getToBitmap();
		if(null == myByte) {
			fb.display(titleView, map.getToPath());
		}else{
			titleView.setImageBitmap(BitmapFactory.decodeByteArray(myByte, 0, myByte.length));
		}
		
		RadioGroup radioGroup = (RadioGroup) view
			.findViewById(R.id.exercise_single_radiogroup);
		String answer = map.getAnswer();
		// 用户没有填写
		if (answer == null || answer.equals("") || answer.equals("null") || answer.equals("-1")) {
		}else{
			int selectIndex = Integer.valueOf(answer);
			
			int count = radioGroup.getChildCount();
			radioGroup.clearCheck();
			if (selectIndex >= 0 && selectIndex < count) {
			
				RadioButton radioBtn = (RadioButton) radioGroup
						.getChildAt(selectIndex);
				radioBtn.setChecked(true);
				radioBtn.isChecked();
				System.out.println(radioBtn.isChecked());
			}
		}
		
		TextView myTextView = (TextView) view
				.findViewById(R.id.teacher_text);
		String toAnswer = map.getToAnswer();
		if(toAnswer.indexOf("0") > 0) {
			toAnswer = "A";
		}else if(toAnswer.indexOf("1") > 0) {
			toAnswer = "B";
		}else if(toAnswer.indexOf("2") > 0) {
			toAnswer = "C";
		}else if(toAnswer.indexOf("3") > 0) {
			toAnswer = "D";
		}
		myTextView.setText("正确答案:\t\t"+toAnswer);
		
		linear.addView(view);

	}
	
	
	/**
	 * 增加多选
	 * 
	 * @param linear
	 * @param map
	 */
	private void addMultilSelect(final LinearLayout linear,
			final Homework map) {

		View view = LayoutInflater.from(context).inflate(
				R.layout.homework_multi_selection, null);
		// image
		ImageView titleView = (ImageView) view
				.findViewById(R.id.exercise_title);

		byte[] myByte = map.getToBitmap();
		if(null == myByte) {
			fb.display(titleView, map.getToPath());
		}else{
			titleView.setImageBitmap(BitmapFactory.decodeByteArray(myByte, 0, myByte.length));
		}
		LinearLayout linearTemp = (LinearLayout) view
		.findViewById(R.id.exercise_single_radiogroup);
		linearTemp.removeAllViews();
		
		String[] results = map.getToOption().split(",");
		for (int i = 0; i < results.length; i++) {
			int index = Integer.valueOf(results[i]);
			String result = changeIndexToChar(index) + "";
			CheckBox checkBox =  (CheckBox) LayoutInflater.from(context).inflate(R.layout.view_checkbox, null);
			checkBox.setText(result);
			linearTemp.addView(checkBox);
		}
		
		// 设置学生多选答案
		String answer = map.getAnswer();
		// 没有回答
		if (answer == null || answer.equals("") || answer.equals("null") || answer.equals("-1")) {
			
		}else{
			String[] option = answer.split(",");
			
			int count = linearTemp.getChildCount();
			for (int i = 0; i < option.length; i++) {
				Integer selectIndex = Integer.valueOf(option[i]);
				if (selectIndex >= 0 && selectIndex < count) {
					CheckBox checkBox = (CheckBox) linearTemp
							.getChildAt(selectIndex);
					checkBox.setChecked(true);
				}

			}
		}

		// 设置正确答案
		TextView myTextView = (TextView) view
				.findViewById(R.id.teacher_text);
		String toAnswer = map.getToAnswer();
		try {
			JSONArray jesonArry = new JSONArray(toAnswer);
			int count = jesonArry.length();
			if(count > 0) {
				toAnswer = jesonArry.getString(0);
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		String[] strArray1 = toAnswer.split(",");
		String b1 = "";
		for(int k=0;k<strArray1.length;k++) {
			if(strArray1[k].equals("0")) {
				strArray1[k] = "A";
			}else if(strArray1[k].equals("1")) {
				strArray1[k] = "B";
			}else if(strArray1[k].equals("2")) {
				strArray1[k] = "C";
			}else if(strArray1[k].equals("3")) {
				strArray1[k] = "D";
			}else if(strArray1[k].equals("4")) {
				strArray1[k] = "E";
			}else if(strArray1[k].equals("5")) {
				strArray1[k] = "F";
			}else if(strArray1[k].equals("6")) {
				strArray1[k] = "G";
			}else if(strArray1[k].equals("7")) {
				strArray1[k] = "H";
			}
			if(k == strArray1.length - 1){
				b1 = b1 + strArray1[k];
			}else{
				b1 = b1 + strArray1[k] + ",";
			}
		}
		toAnswer = b1;
		myTextView.setText("正确答案:\t\t"+toAnswer);
		linear.addView(view);

	}
	
	/**
	 * 增加判断
	 * 
	 * @param linear
	 * @param map
	 */
	private void addJudgeSelect(final LinearLayout linear,
			final Homework map) {
		View view = LayoutInflater.from(context).inflate(
				R.layout.homework_judge_selection, null);
		// title
		// image
		ImageView titleView = (ImageView) view
				.findViewById(R.id.exercise_title);

		byte[] myByte = map.getToBitmap();
		if(null == myByte) {
			fb.display(titleView, map.getToPath());
		}else{
			titleView.setImageBitmap(BitmapFactory.decodeByteArray(myByte, 0, myByte.length));
		}
		
		RadioGroup radioGroup = (RadioGroup) view
				.findViewById(R.id.exercise_single_radiogroup);
		String answer = map.getAnswer();
		// 用户没有填写
		if (answer == null || answer.equals("") || answer.equals("null") || answer.equals("-1")) {
			
		}else{
			int selectIndex = Integer.valueOf(answer);
//			int count = radioGroup.getChildCount();
			radioGroup.clearCheck();
			RadioButton radioBtn = (RadioButton) radioGroup
			.getChildAt(selectIndex);
			radioBtn.setChecked(true);
		}
		
		TextView myTextView = (TextView) view
				.findViewById(R.id.teacher_text);
		String toAnswer = map.getToAnswer();
		if(toAnswer.indexOf("0") > 0) {
			toAnswer = "对";
		}else if(toAnswer.indexOf("1") > 0) {
			toAnswer = "错";
		}
		myTextView.setText("正确答案:\t\t"+toAnswer);
		
		linear.addView(view);

	}
	
	/**
	 * 增加填空
	 * 
	 * @param linear
	 * @param map
	 */
	private void addFullSelect(final LinearLayout linear,
			final Homework map) {
		View view = LayoutInflater.from(context).inflate(
				R.layout.homework_full_selection, null);
		
		// image
		ImageView titleView = (ImageView) view
				.findViewById(R.id.exercise_title);

		byte[] myByte = map.getToBitmap();
		if(null == myByte) {
			fb.display(titleView, map.getToPath());
		}else{
			titleView.setImageBitmap(BitmapFactory.decodeByteArray(myByte, 0, myByte.length));
		}
		
		LinearLayout linearTemp = (LinearLayout) view
			.findViewById(R.id.exercise_full_linear);
		linearTemp.removeAllViews();
		// optin选项 用;分割
		String[] results = map.getToOption().split(",");
		int size = results.length;
		for (int y = 0; y < size; y++) {
			LinearLayout.LayoutParams paramsLinear = new LinearLayout.LayoutParams(
					LinearLayout.LayoutParams.FILL_PARENT,
					LinearLayout.LayoutParams.WRAP_CONTENT);
			LinearLayout l = new LinearLayout(context);
			if(y!= 0){
				paramsLinear.setMargins(0, 5, 0, 0);
			}
			l.setOrientation(LinearLayout.HORIZONTAL);
			linearTemp.addView(l, paramsLinear);
			// add textview
			LinearLayout.LayoutParams paramsText = new LinearLayout.LayoutParams(
					LinearLayout.LayoutParams.WRAP_CONTENT,
					LinearLayout.LayoutParams.WRAP_CONTENT);
			TextView textIndex = new TextView(context);
			textIndex.setText("(" + (y+1) + ")");
			textIndex.setTextColor(0xff375c6c);
			textIndex.setTextSize(15);
			l.addView(textIndex, paramsText);
			// add edittext
			LinearLayout.LayoutParams paramsEdit = new LinearLayout.LayoutParams(
					LinearLayout.LayoutParams.FILL_PARENT,
					LinearLayout.LayoutParams.WRAP_CONTENT);
			paramsEdit.setMargins(10, 0, 0, 0);
			EditText editValue = new EditText(context);
			editValue.setBackgroundResource(R.drawable.subject);
			editValue.setSingleLine();
			l.addView(editValue, paramsEdit);

		}
		
		String answer = map.getAnswer();
		try {
			JSONArray ja = new JSONArray(answer);
			for (int i = 0; i < ja.length(); i++) {
				String result = ja.getString(i);
				LinearLayout l = (LinearLayout) linearTemp.getChildAt(i);
				EditText e = (EditText) l.getChildAt(1);
				e.setText(result);

			}
		} catch (JSONException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
		TextView myTextView = (TextView) view
				.findViewById(R.id.teacher_text);
		String toAnswer = map.getToAnswer();
		
		try {
			JSONArray jesonArry = new JSONArray(toAnswer);
			int count = jesonArry.length();
			String b = "\n";
			for(int j=0;j<count;j++) {
				if(j == count-1) {
					b = b + (j+1) + ")、" + jesonArry.getString(j);
				}else{
					b = b + (j+1) + ")、"  + jesonArry.getString(j) + "\n";
				}
				
			}
			toAnswer = b;
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		myTextView.setText("正确答案:\t\t"+toAnswer);
		linear.addView(view);

	}
	
	/**
	 * 增加简答
	 * 
	 * @param linear
	 * @param map
	 */
	private void addShortAnswerSelect(final LinearLayout linear,
			final Homework map) {
		View view = LayoutInflater.from(context).inflate(
				R.layout.homework_short_answer, null);
		// image
		ImageView titleView = (ImageView) view
				.findViewById(R.id.exercise_title);
		
		byte[] myByte = map.getToBitmap();
		if(null == myByte) {
			fb.display(titleView, map.getToPath());
		}else{
			titleView.setImageBitmap(BitmapFactory.decodeByteArray(myByte, 0, myByte.length));
		}
		
		linear.addView(view);

	}
	
	/**
	 * 
	 * @return
	 */
	public static char changeIndexToChar(int i) {
		char result = 'A';
		int a = Integer.valueOf(result) + i;
		result = (char) a;
		return result;
	}
}
