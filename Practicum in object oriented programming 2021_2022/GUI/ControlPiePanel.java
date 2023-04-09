package Olympiad;

import java.awt.Color;
import java.awt.GridLayout;

import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JTextField;
import javax.swing.SwingConstants;

public class ControlPiePanel extends JPanel {
	
	private JTextField sportField;
	private JLabel sportLabel;
	private JButton sportInfo;
	
	private JTextField yearField;
	private JLabel yearLabel;
	private JButton yearInfo;
	
	private JTextField typeField;
	private JLabel typeLabel;
	private JButton typeInfo;
	
	private JTextField medalField;
	private JLabel medalLabel;
	private JButton medalInfo;
	
	public ControlPiePanel() {
		this.setLayout(new GridLayout(4, 3));
		
		sportInfo = new JButton("Info");
		sportInfo.setBackground(Color.cyan);
		sportInfo.addActionListener(l -> {
			JOptionPane.showMessageDialog(null, "Enter the name of the sport. Empty field means "
					+ "that this filter doesn't count.");
		});
		sportInfo.setFocusable(false);
		sportLabel = new JLabel("Sport:");
		sportLabel.setHorizontalAlignment(SwingConstants.CENTER);
		this.add(sportLabel);
		sportField = new JTextField();
		this.add(sportField);
		this.add(sportInfo);
		
		yearInfo = new JButton("Info");
		yearInfo.setBackground(Color.cyan);
		yearInfo.addActionListener(l -> {
			JOptionPane.showMessageDialog(null, "Enter the year of the Olympic games. Empty field or "
					+ "if the year isn't an integer means that this filter doesn't count.");
		});
		yearInfo.setFocusable(false);
		yearLabel = new JLabel("Year:");
		yearLabel.setHorizontalAlignment(SwingConstants.CENTER);
		this.add(yearLabel);
		yearField = new JTextField();
		this.add(yearField);
		this.add(yearInfo);
		
		typeInfo = new JButton("Info");
		typeInfo.setBackground(Color.cyan);
		typeInfo.addActionListener(l -> {
			JOptionPane.showMessageDialog(null, "Enter individual/team. Empty field or something else "
					+ "than individual/team (case insensitive) means that this filter doesn't count.");
		});
		typeInfo.setFocusable(false);
		typeLabel = new JLabel("Type:");
		typeLabel.setHorizontalAlignment(SwingConstants.CENTER);
		this.add(typeLabel);
		typeField = new JTextField();
		this.add(typeField);
		this.add(typeInfo);
		
		medalInfo = new JButton("Info");
		medalInfo.setBackground(Color.cyan);
		medalInfo.addActionListener(l -> {
			JOptionPane.showMessageDialog(null, "Enter gold/silver/bronze/none. Empty field or something else "
					+ "than gold/silver/bronze/none (case insensitive) means that this filter doesn't count.");
		});
		medalInfo.setFocusable(false);
		medalLabel = new JLabel("Medal:");
		medalLabel.setHorizontalAlignment(SwingConstants.CENTER);
		this.add(medalLabel);
		medalField = new JTextField();
		this.add(medalField);
		this.add(medalInfo);
		
		this.setBackground(Color.cyan);
	}
	
	public String getFilteringData() {
		StringBuilder sb = new StringBuilder();
		sb.append("Pie!");
		
		String field = sportField.getText();
		if (field.equals("")) {
			sb.append("-1!");
		}
		else {
			sb.append(field + "!");
		}
		
		field = yearField.getText();
		if (field.equals("")) {
			sb.append("-1!");
		}
		else {
			try {
				Integer.parseInt(field);
				sb.append(field + "!");
			}
			catch (NumberFormatException e) {
				// entered number isn't an integer, so it's like there isn't an input
				sb.append("-1!");
			}
		}
		
		field = typeField.getText();
		if (field.toLowerCase().equals("individual")) {
			sb.append("Individual!");
		}
		else if (field.toLowerCase().equals("team")) {
			sb.append("Team!");
		}
		else {
			sb.append("-1!");
		}
		
		field = medalField.getText();
		if (field.toLowerCase().equals("gold")) {
			sb.append("Gold");
		}
		else if (field.toLowerCase().equals("silver")) {
			sb.append("Silver");
		}
		else if (field.toLowerCase().equals("bronze")) {
			sb.append("Bronze");
		}
		else if (field.toLowerCase().equals("none")) {
			sb.append("None");
		}
		else  {
			sb.append("-1");
		}
		
		return sb.toString();
	}

}
