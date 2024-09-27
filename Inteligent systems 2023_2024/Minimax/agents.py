import random
import time
from state import State
import math
from functools import cmp_to_key

class Agent:
    ident = 0

    def __init__(self):
        self.id = Agent.ident
        Agent.ident += 1

    def get_chosen_column(self, state, max_depth):
        pass


class Human(Agent):
    pass


class ExampleAgent(Agent):
    def get_chosen_column(self, state, max_depth):
        time.sleep(random.random())
        columns = state.get_possible_columns()
        return columns[random.randint(0, len(columns) - 1)]
    

def compare_states(x, y):
    if x[1] != y[1]:
        return y[1] - x[1]
    elif abs(3 - x[2]) != abs(3 - y[2]):
        return abs(3 - x[2]) - abs(3 - y[2])
    else:
        return x[2] - y[2]

class MinimaxABAgent(Agent):
    def get_chosen_column(self, state: State, max_depth):
        score, column = self.minimax(state, state.get_next_on_move(), 0, max_depth, -math.inf, math.inf)
        return column

    def minimax(self, state: State, ident, depth, max_depth, alpha, beta):
        if (depth == max_depth and max_depth != 0) or state.get_state_status() != None:
            return state.get_evaluation(max_depth - depth + 1), -1

        # sorting successors of current state
        comparing_function = cmp_to_key(compare_states)
        multiplicator = -1 if ident == State.YEL else 1
        unsorted_next_states = [[state.generate_successor_state(column), 0, column] for column in state.get_possible_columns()]
        for s in unsorted_next_states:
            s[1] = s[0].get_evaluation(max_depth - depth + 1) * multiplicator
        next_states = sorted(unsorted_next_states, key=comparing_function)
        return_column = -1

        if ident == state.RED:
            score = -math.inf
            for s in next_states:
                curr_score, curr_column = self.minimax(s[0], 1 - ident, depth + 1, max_depth, alpha, beta)
                if curr_score > score:
                    score = curr_score
                    return_column = s[2]
                alpha = max(alpha, score)
                if alpha >= beta:
                    break
            return score, return_column

        else:
            score = math.inf
            for s in next_states:
                curr_score, curr_column = self.minimax(s[0], 1 - ident, depth + 1, max_depth, alpha, beta)
                if curr_score < score:
                    score = curr_score
                    return_column = s[2]
                beta = min(beta, score)
                if alpha >= beta:
                    break
            return score, return_column
        

class NegascoutAgent(Agent):
    def get_chosen_column(self, state: State, max_depth):
        score, column = self.negascout(state, state.get_next_on_move(), 0, max_depth, -math.inf, math.inf)
        return column
        
    def negascout(self, state: State, ident, depth, max_depth, alpha, beta):
        if (depth == max_depth and max_depth != 0) or state.get_state_status() != None:
            val = state.get_evaluation(max_depth - depth + 1)
            if ident == State.YEL:
                val = -val
            return val, -1

        # sorting successors of current state
        comparing_function = cmp_to_key(compare_states)
        multiplicator = -1 if ident == State.YEL else 1
        unsorted_next_states = [[state.generate_successor_state(column), 0, column] for column in state.get_possible_columns()]
        for s in unsorted_next_states:
            s[1] = s[0].get_evaluation(max_depth - depth) * multiplicator
        next_states = sorted(unsorted_next_states, key=comparing_function)
        return_column = -1

        score = -math.inf
        return_column = -1
        for i, s in enumerate(next_states):
            if i == 0:
                val, col = self.negascout(s[0], 1 - ident, depth + 1, max_depth, -beta, -alpha)
                val = -val
            else:
                val, col = self.negascout(s[0], 1 - ident, depth + 1, max_depth, -alpha - 1, -alpha)
                val = -val
                if alpha < val < beta:
                    val, col = self.negascout(s[0], 1 - ident, depth + 1, max_depth, -beta, -alpha)
                    val = -val

            if score < val:
                score = val
                return_column = s[2]
            if alpha < score:
                alpha = score
            if alpha >= beta:
                break

        return score, return_column
