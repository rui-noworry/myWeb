package dkt.student.view;

import java.util.HashMap;

import org.json.JSONArray;
import org.json.JSONException;

import dkt.student.R;
import dkt.student.model.Homework;
import dkt.student.util.bitmap.FinalBitmap;
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
	 * 添加各种习题和答案 单选1 多选2 填空3 判断4
	 * 
	 * @param linear
	 * @param map
	 */
	public void addExerciseView(LinearLayout linear,
			Homework exercise, boolean hasAnswer) {
		int style = Integer.parseInt(exercise.getToType());
		if (style == 1) {
			addSingleSelect(linear, exercise, hasAnswer);
		} else if (style == 2) {
			addMultilSelect(linear, exercise, hasAnswer);
		} else if (style == 4) {
			addJudgeSelect(linear, exercise, hasAnswer);
		} else if (style == 3) {
			addFullSelect(linear, exercise, hasAnswer);
		}
	}

	/**
	 * 获得各种习题 单选1 多选2 填空3 判断4
	 * 
	 * @param linear
	 * @param map
	 */
	public HashMap<String, Object> getExerciseViewValue(LinearLayout linear,
			Homework exercise, int index) {
		HashMap<String, Object> map = new HashMap<String, Object>();
		int style = Integer.parseInt(exercise.getToType());
		if (style == 1) {
			map = getSingleSelect(linear, exercise, index);
		} else if (style == 2) {
			map = getMultilSelect(linear, exercise, index);
		} else if (style == 4) {
			map = getJudgeSelect(linear, exercise, index);
		} else if (style == 3) {
			map = getFullSelect(linear, exercise, index);
		}
		return map;
	}

	/**
	 * 删除各种习题的答案 单选1 多选2 填空3 判断4
	 * 
	 * @param linear
	 * @param map
	 */
	public void clearExerciseView(LinearLayout linear,
			Homework exercise, int index) {
		int style = Integer.parseInt(exercise.getToType());
		if (style == 1) {
			clearSingleSelect(linear, exercise, index);
		} else if (style == 2) {
			clearMultilSelect(linear, exercise, index);
		} else if (style == 4) {
			clearJudgeSelect(linear, exercise, index);
		} else if (style == 3) {
			clearFullSelect(linear, exercise, index);
		}
	}

	/**
	 * 设置习题的答案
	 */
	public void setExerciseViewValue(LinearLayout linear,
			Homework exercise, int index) {
		int style = Integer.parseInt(exercise.getToType());
		
		if (style == 1) {
			setSingleSelect(linear, exercise, index);
		} else if (style == 2) {
			setMultilSelect(linear, exercise, index);
		} else if (style == 4) {
			setJudgeSelect(linear, exercise, index);
			System.out.println("判断题答案"+exercise.getAnswer());
		} else if (style == 3) {
			setFullSelect(linear, exercise, index);
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
				R.layout.exercise_single_selection, null);
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
		radioGroup.removeAllViews();

		String[] results = map.getToOption().split(",");
		for (int i = 0; i < results.length; i++) {
			int index = Integer.valueOf(results[i]);
			String result = changeIndexToChar(index) + "";
			
			RadioButton radioBtn = (RadioButton) LayoutInflater.from(context).inflate(R.layout.view_radiobutton, null);
//			RadioButton radioBtn = new RadioButton(context);
			radioBtn.setText(result);
//			radioBtn.setTextColor(0xff375c6c);
			radioGroup.addView(radioBtn);
		}
		linear.addView(view);

	}

	/**
	 * 增加单选 和答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void addSingleSelect(final LinearLayout linear,
			final Homework map, boolean hasAnswer) {
		View view = LayoutInflater.from(context).inflate(
				R.layout.exercise_single_selection, null);
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
		radioGroup.removeAllViews();

		String[] results = map.getToOption().split(",");
		for (int i = 0; i < results.length; i++) {
			int index = Integer.valueOf(results[i]);
			String result = changeIndexToChar(index) + "";
			RadioButton radioBtn = new RadioButton(context);
			radioBtn.setText(result);
			radioBtn.setTextColor(0xff375c6c);
			radioGroup.addView(radioBtn);
		}
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
				R.layout.exercise_multi_selection, null);
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
//			CheckBox checkBox = new CheckBox(context);
			checkBox.setText(result);
//			checkBox.setTextColor(0xff375c6c);
			linearTemp.addView(checkBox);
		}

		linear.addView(view);

	}

	/**
	 * 增加多选 和答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void addMultilSelect(final LinearLayout linear,
			final Homework map, boolean hasAnswer) {

		View view = LayoutInflater.from(context).inflate(
				R.layout.exercise_multi_selection, null);
		// title
		String title = map.getToTitle();
		TextView titleView = (TextView) view.findViewById(R.id.exercise_title);
		titleView.setText(title);
		LinearLayout linearTemp = (LinearLayout) view
				.findViewById(R.id.exercise_single_radiogroup);
		linearTemp.removeAllViews();

		try {
			JSONArray jo2 = new JSONArray(map.getToOption());
			int size = jo2.length();
			for (int y = 0; y < size; y++) {
				String result = jo2.get(y).toString();
				CheckBox checkBox = new CheckBox(context);
				checkBox.setText(result);
				checkBox.setTextColor(0xff375c6c);
				linearTemp.addView(checkBox);

			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
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
				R.layout.exercise_judge_selection, null);
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
				R.layout.exercise_short_answer_selection, null);
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
	 * 增加判断 和答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void addJudgeSelect(final LinearLayout linear,
			final Homework map, boolean hasAnswer) {
		View view = LayoutInflater.from(context).inflate(
				R.layout.exercise_judge_selection, null);
		// title
		String title = map.getToTitle();
		TextView titleView = (TextView) view.findViewById(R.id.exercise_title);
		titleView.setText(title);
		linear.addView(view);

		RadioButton r1 = (RadioButton) view
				.findViewById(R.id.exercise_single_radiobtn1);
		RadioButton r2 = (RadioButton) view
				.findViewById(R.id.exercise_single_radiobtn2);
		try {
			JSONArray jo2 = new JSONArray(map.getToOption());
			int size = jo2.length();
			for (int y = 0; y < size; y++) {
				String result = jo2.get(y).toString();
				if (y == 0) {
					r1.setText(result);
				} else if (y == 1) {
					r2.setText(result);
				}

			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
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
				R.layout.exercise_full_selection, null);
		LinearLayout linearTemp = (LinearLayout) view
				.findViewById(R.id.exercise_full_linear);
		// image
		ImageView titleView = (ImageView) view
				.findViewById(R.id.exercise_title);

		byte[] myByte = map.getToBitmap();
		if(null == myByte) {
			fb.display(titleView, map.getToPath());
		}else{
			titleView.setImageBitmap(BitmapFactory.decodeByteArray(myByte, 0, myByte.length));
		}
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
		linear.addView(view);

	}

	/**
	 * 增加填空 和答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void addFullSelect(final LinearLayout linear,
			final Homework map, boolean hasAnswer) {
		View view = LayoutInflater.from(context).inflate(
				R.layout.exercise_full_selection, null);
		LinearLayout linearTemp = (LinearLayout) view
				.findViewById(R.id.exercise_full_linear);
		// title
		String title = map.getToTitle();
		TextView titleView = (TextView) view.findViewById(R.id.exercise_title);
		titleView.setText(title);
		linearTemp.removeAllViews();
		// optin选项 用;分割

		try {
			JSONArray jo2 = new JSONArray(map.getToOption());
			int size = jo2.length();
			for (int y = 0; y < size; y++) {
				LinearLayout.LayoutParams paramsLinear = new LinearLayout.LayoutParams(
						LinearLayout.LayoutParams.FILL_PARENT,
						LinearLayout.LayoutParams.WRAP_CONTENT);
				LinearLayout l = new LinearLayout(context);
				l.setOrientation(LinearLayout.HORIZONTAL);
				linearTemp.addView(l, paramsLinear);
				// add textview
				LinearLayout.LayoutParams paramsText = new LinearLayout.LayoutParams(
						LinearLayout.LayoutParams.WRAP_CONTENT,
						LinearLayout.LayoutParams.WRAP_CONTENT);
				TextView textIndex = new TextView(context);
				textIndex.setText("(" + y + ")");
				textIndex.setTextColor(0xff375c6c);
				textIndex.setTextSize(15);
				l.addView(textIndex, paramsText);
				// add edittext
				LinearLayout.LayoutParams paramsEdit = new LinearLayout.LayoutParams(
						LinearLayout.LayoutParams.FILL_PARENT,
						LinearLayout.LayoutParams.WRAP_CONTENT);
				EditText editValue = new EditText(context);
				editValue.setBackgroundResource(R.drawable.subject);
				l.addView(editValue, paramsEdit);

			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		linear.addView(view);

	}

	/**
	 * 得到单选 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private HashMap<String, Object> getSingleSelect(LinearLayout linear,
			final Homework map, int index) {
		HashMap<String, Object> mapValue = new HashMap<String, Object>();
		
		RadioGroup radioGroup = (RadioGroup) linear.getChildAt(0)
				.findViewById(R.id.exercise_single_radiogroup);
		int selectIndex = -1;
		int count = radioGroup.getChildCount();
		for (int i = 0; i < count; i++) {
			RadioButton radioBtn = (RadioButton) radioGroup.getChildAt(i);
			if (radioBtn.isChecked()) {
				selectIndex = i;
				break;
			}

		}
		if (selectIndex == -1) {
			mapValue.put("answer", "-1");
		} else {
			mapValue.put("answer", selectIndex);
		}
		mapValue.put("index", index);
		mapValue.put("answer", selectIndex);
		mapValue.put("co_id", map.getToId());
		mapValue.put("co_style", map.getToType());
		return mapValue;
	}

	/**
	 * 删除单选 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void clearSingleSelect(final LinearLayout linear,
			final Homework map, int index) {
		RadioGroup radioGroup = (RadioGroup) linear.getChildAt(index)
				.findViewById(R.id.exercise_single_radiogroup);
		int count = radioGroup.getChildCount();
		for (int i = 0; i < count; i++) {
			RadioButton radioBtn = (RadioButton) radioGroup.getChildAt(i);
			radioBtn.setChecked(false);

		}
	}

	/**
	 * 得到多选 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private HashMap<String, Object> getMultilSelect(final LinearLayout linear,
			final Homework map, int index) {
		HashMap<String, Object> mapValue = new HashMap<String, Object>();
		final StringBuffer sb = new StringBuffer();//getChildAt(index)
		LinearLayout linearTemp = (LinearLayout) linear.getChildAt(0)
				.findViewById(R.id.exercise_single_radiogroup);
		int count = linearTemp.getChildCount();

		int selectIndex = -1;
		for (int i = 0; i < count; i++) {
			CheckBox checkBox = (CheckBox) linearTemp.getChildAt(i);
			if (checkBox.isChecked()) {
				sb.append(i);
				selectIndex = 100;
				if (i != count - 1) {
					sb.append(",");
				}
			}

		}
		String result = sb.toString();

//		if (result.endsWith("")) {
////			result = result.substring(0, result.length() - 1);
//			result = "-1";
//		}

		if (selectIndex == -1) {
			mapValue.put("answer", "-1");
		} else {
			mapValue.put("answer", result);
		}
		mapValue.put("index", index);
		mapValue.put("co_id", map.getToId());
		mapValue.put("co_style", map.getToType());
		return mapValue;
	}

	/**
	 * 删除多选 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void clearMultilSelect(final LinearLayout linear,
			final Homework map, int index) {

		LinearLayout linearTemp = (LinearLayout) linear.getChildAt(index)
				.findViewById(R.id.exercise_single_radiogroup);
		int count = linearTemp.getChildCount();

		for (int i = 0; i < count; i++) {
			CheckBox checkBox = (CheckBox) linearTemp.getChildAt(i);
			checkBox.setChecked(false);
		}

	}

	/**
	 * 得到判断答案
	 * 
	 * @param linear
	 * @param map
	 */
	private HashMap<String, Object> getJudgeSelect(final LinearLayout linear,
			final Homework map, int index) {
		HashMap<String, Object> mapValue = new HashMap<String, Object>();

		RadioGroup radioGroup = (RadioGroup) linear.getChildAt(0)
				.findViewById(R.id.exercise_single_radiogroup);
		int[] radioId = { R.id.exercise_single_radiobtn1,
				R.id.exercise_single_radiobtn2 };
		int selectIndex = -1;
		for (int i = 0; i < radioId.length; i++) {
			if (radioGroup.getCheckedRadioButtonId() == radioId[i]) {
				selectIndex = i + 1;
				break;
			}
		}

		if (selectIndex == -1) {
			mapValue.put("answer", "-1");
		} else {
			mapValue.put("answer", selectIndex);
		}
		mapValue.put("index", index);
		mapValue.put("co_id", map.getToId());
		mapValue.put("co_style", map.getToType());
		return mapValue;
	}

	/**
	 * 删除判断答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void clearJudgeSelect(final LinearLayout linear,
			final Homework map, int index) {

		RadioGroup radioGroup = (RadioGroup) linear.getChildAt(index)
				.findViewById(R.id.exercise_single_radiogroup);
		int count = radioGroup.getChildCount();
		for (int i = 0; i < count; i++) {
			RadioButton radioBtn = (RadioButton) radioGroup.getChildAt(i);
			radioBtn.setChecked(false);

		}

	}

	/**
	 * 得到填空 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private HashMap<String, Object> getFullSelect(final LinearLayout linear,
			final Homework map, int index) {
		HashMap<String, Object> mapValue = new HashMap<String, Object>();

		LinearLayout linearTemp = (LinearLayout) linear.getChildAt(0)
				.findViewById(R.id.exercise_full_linear);
		int count = linearTemp.getChildCount();

		JSONArray ja = new JSONArray();
		for (int i = 0; i < count; i++) {
			LinearLayout l = (LinearLayout) linearTemp.getChildAt(i);
			EditText e = (EditText) l.getChildAt(1);

			ja.put(e.getText());
		}
		mapValue.put("index", index);
		mapValue.put("answer", ja.toString());
		mapValue.put("co_id", map.getToId());
		mapValue.put("co_style", map.getToType());
		return mapValue;
	}

	/**
	 * 删除填空 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void clearFullSelect(final LinearLayout linear,
			final Homework map, int index) {
		LinearLayout linearTemp = (LinearLayout) linear.getChildAt(index)
				.findViewById(R.id.exercise_full_linear);
		int count = linearTemp.getChildCount();
		for (int i = 0; i < count; i++) {
			LinearLayout l = (LinearLayout) linearTemp.getChildAt(i);
			EditText e = (EditText) l.getChildAt(1);
			e.setText("");
		}
	}

	/**
	 * 设置单选 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void setSingleSelect(final LinearLayout linear,
			final Homework map, int index) {
		RadioGroup radioGroup = (RadioGroup) linear
				.findViewById(R.id.exercise_single_radiogroup);
		String answer = map.getAnswer();
		// 用户没有填写
		if (answer == null || answer.equals("") || answer.equals("null") || answer.equals("-1")) {
			return;
		}
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

	/**
	 * 设置多选 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void setMultilSelect(final LinearLayout linear,
			final Homework map, int index) {

		String answer = map.getAnswer();
		// 用户没有填写
		if (answer == null || answer.equals("") || answer.equals("null") || answer.equals("-1")) {
			return;
		}

		String[] option = answer.split(",");
		LinearLayout linearTemp = (LinearLayout) linear
				.findViewById(R.id.exercise_single_radiogroup);
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

	/**
	 * 设置判断答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void setJudgeSelect(final LinearLayout linear,
			final Homework map, int index) {

		RadioGroup radioGroup = (RadioGroup) linear
				.findViewById(R.id.exercise_single_radiogroup);
		String answer = map.getAnswer();
		// 用户没有填写
		if (answer == null || answer.equals("") || answer.equals("null") || answer.equals("-1")) {
			return;
		}

		int selectIndex = Integer.valueOf(answer);
		int count = radioGroup.getChildCount();
		radioGroup.clearCheck();
		RadioButton radioBtn = (RadioButton) radioGroup
		.getChildAt(selectIndex);
		radioBtn.setChecked(true);
	}

	/**
	 * 设置填空 答案
	 * 
	 * @param linear
	 * @param map
	 */
	private void setFullSelect(final LinearLayout linear,
			final Homework map, int index) {

		LinearLayout linearTemp = (LinearLayout) linear
				.findViewById(R.id.exercise_full_linear);
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
