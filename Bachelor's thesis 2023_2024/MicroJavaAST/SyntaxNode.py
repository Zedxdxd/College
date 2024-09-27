# The base class for all classes that represent the nodes of the abstract syntax
# tree

from abc import ABC, abstractmethod


class SyntaxNode(ABC):
	def __repr__(self):
		return ""

	@abstractmethod
	def accept(self, visitor):
		"""
		Call the visit method of this node.

		Args:
			visitor (Visitor): A visitor that implements the visit method for
				this node
		"""
		pass

	@abstractmethod
	def emit_code(self, tab):
		"""
		Returns a string that is part of the code this node represents.

		Args:
			tab (str): If this node is a newline_adder, this is the ident after
				the new line.
		"""
		pass

	@abstractmethod
	def traverse_bottom_up(self, visitor):
		"""
		Traverse the tree that this node represents in a bottom-up manner.

		Args:
			visitor (Visitor): A visitor that implements the visit method for
				this node
		"""
		pass