package Olympiad;

import java.awt.Color;
import java.awt.Graphics;

import javax.swing.JPanel;

public class PieChart extends Graph {
	
	// fixed array of colors for drawing
	private static Color[] colors = {Color.red, Color.yellow, Color.blue, Color.orange, 
					Color.cyan, Color.darkGray, Color.green, Color.magenta, Color.black, Color.pink, Color.gray};
	
	private final static int NUMBER_COLORS = colors.length;

	@Override
	public void draw(Graphics g, JPanel panel) {
		
		if (data == null) return;

		int r = panel.getWidth() / 4;
		int xMid = panel.getWidth() / 2;
		int yMid = panel.getHeight() / 2;
		int x = xMid - r;
		int y = yMid - r;
		
		// holds how many competitors are there, for getting the percent
		double sumAll = 0;
		for (String key : data.keySet()) {
			sumAll += data.get(key);
		}
		int startAngle = 222;//(int)(Math.random() * 361); uzeh 222 random skroz
		int lastAngle = 0; // sums all percent angles, so that i can get the arc for others
		int i = 0;
		for (String key : data.keySet()) {
			g.setColor(colors[i]);
			
			int arcAngle = (int)(360 * data.get(key)/sumAll);
			g.fillArc(x, y, 2*r, 2*r, startAngle, arcAngle);
			
			int xString = (int)(xMid + r*Math.cos(Math.toRadians(startAngle + arcAngle/2)));
			int yString = (int)(yMid - r*Math.sin(Math.toRadians(startAngle + arcAngle/2)));
			
			// adjusting the coordinates for countryName based on the quadrant
			if (xString - xMid >= 0 && yString - yMid <= 0) {
				// first quadrant
				xString += g.getFont().getSize();
			}
			else if (xString - xMid <= 0 && yString - yMid <= 0) {
				// second quadrant
				xString -= 6*key.length();
			}
			else if (xString - xMid <= 0 && yString - yMid >= 0) {
				// third quadrant
				xString -= 6*key.length();
				yString += g.getFont().getSize();
			}
			else {
				// fourth quadrant
				yString += g.getFont().getSize();
			}
			
			g.setColor(Color.black);
			g.drawString(key, xString, yString);
			
			startAngle += arcAngle;
			lastAngle += arcAngle;
			i++;
			if (arcAngle <= 5 || i == NUMBER_COLORS - 1) {
				break;
			}
		}
		String key = "Others";
		g.setColor(colors[i]);
		lastAngle = 360 - lastAngle;
		int xString = (int)(xMid + r*Math.cos(Math.toRadians(startAngle + lastAngle/2)));
		int yString = (int)(yMid - r*Math.sin(Math.toRadians(startAngle + lastAngle/2)));
		
		// adjusting the coordinates for countryName based on the quadrant
		if (xString - xMid >= 0 && yString - yMid <= 0) {
			// first quadrant
			xString += g.getFont().getSize();
		}
		else if (xString - xMid <= 0 && yString - yMid <= 0) {
			// second quadrant
			xString -= 6*key.length();
		}
		else if (xString - xMid <= 0 && yString - yMid >= 0) {
			// third quadrant
			xString -= 6*key.length();
			yString += g.getFont().getSize();
		}
		else {
			// fourth quadrant
			yString += g.getFont().getSize();
		}
		
		g.fillArc(x, y, 2*r, 2*r, startAngle, lastAngle);
		g.setColor(Color.black);
		g.drawString("Others", xString, yString);
		
	}

}
