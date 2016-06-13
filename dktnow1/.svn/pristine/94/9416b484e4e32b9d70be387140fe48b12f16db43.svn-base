package dkt.student.view;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.util.AttributeSet;
import android.view.View;

public class FormForWeekView extends View{

	private int firstX = 15; // 起始点x
	private int firstY = 15; // 起始点y
	private int secondX = 95; // 第二点x
	private int secondY = 65; // 第二点y
	private int widthNum = 8;  // 列
	private int heightNum = 1; // 行
	private int secondSideX = 150; // 第二列的宽
	private int sideY = 50; // 行高
	private int firstSidesX = 80; // 第一列的宽
	
	public FormForWeekView(Context context, AttributeSet attrs) {
		super(context, attrs);
		// TODO Auto-generated constructor stub
	}

	@Override
	protected void onDraw(Canvas canvas) {
		// TODO Auto-generated method stub
		super.onDraw(canvas);
		drawForm(canvas);
	}
	

	private void drawForm(Canvas canvas) {
		Paint paint = new Paint();

		paint.setAntiAlias(true);
		paint.setColor(Color.BLACK);
		paint.setStyle(Paint.Style.STROKE);
		paint.setStrokeWidth(2);
		paint.setStyle(Paint.Style.FILL);
		

		paint.setColor(Color.BLACK);
		paint.setStyle(Paint.Style.STROKE);
		int cellX, cellY, cellBX, cellBY;
		
		for (int i = 0; i < widthNum; i++)
			
			for (int j = 0; j < heightNum; j++) {
				if(0 == i) { // 如果是第一列 宽度为firstSidesX
					cellX = firstX + i * firstSidesX;
					cellY = firstY + j * sideY;
					cellBX = firstX + (i + 1) * firstSidesX;
					cellBY = firstY + (j + 1) * sideY;
				}else{
					cellX = secondX + (i - 1) * secondSideX;
					cellY = secondY + (j - 1) * sideY;
					cellBX = secondX + i * secondSideX;
					cellBY = secondY + j  * sideY;
				}

				canvas.drawRect(cellX, cellY, cellBX, cellBY, paint);
				int cellsNum = i + j * widthNum;			
				drawColorText(canvas, cellX, cellY, cellBX, cellBY, cellsNum);
			}
	}
	
	private void drawColorText(Canvas canvas, int cellX, int cellY, int cellBX,
			int cellBY, int paintColor) {
		switch (paintColor) {
		case 0:
			break;
		case 1:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "星期一");
			break;
		case 2:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "星期二");
			break;
		case 3:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "星期三");
			break;
		case 4:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "星期四");
			break;
		case 5:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "星期五");
			break;
		case 6:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "星期六");
			break;
		case 7:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "星期天");
			break;
		default:
			drawCellText(canvas, cellX, cellY, cellBX, cellBY, "");
			break;
		}
	}
	
	// 绘制单元格中的文字
	private void drawCellText(Canvas canvas, int cellX, int cellY, int cellBX,
			int cellBY, String text) {
		Paint paint = new Paint();
		paint.setFlags(Paint.ANTI_ALIAS_FLAG);
		paint.setColor(Color.BLUE); // 字体颜色
		paint.setTextSize((cellBY - cellY) / 4 * 2); // 字体大小
		int textX = cellX + (cellBX - cellX) / 4;
		int textY = cellBY - (cellBY - cellY) / 3;
		canvas.drawText(text, textX, textY, paint);
	}
}
