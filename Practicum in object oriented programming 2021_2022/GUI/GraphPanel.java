package Olympiad;


import java.awt.Graphics;
import java.util.LinkedHashMap;

import javax.swing.JPanel;

public class GraphPanel extends JPanel {

	private Graph graph = new PieChart();
	
	
	@Override
	public void paintComponent(Graphics g) {
		graph.draw(g, this);
	}
	
	public void setGraph(Graph g) {
		graph = g;
	}
	
	// set drawing data
	public void setData(LinkedHashMap<String, Double> map) {
		graph.setData(map);
	}

	// set start and end year for drawing
	public void setStartEndYear(int startYear, int endYear) {
		if (graph instanceof XYGraph) {
			((XYGraph)graph).setStartEndYear(startYear, endYear);
		}
		
	}
}
