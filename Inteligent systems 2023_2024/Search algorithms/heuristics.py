from math import sqrt

class Heuristic:
    def get_evaluation(self, state):
        pass


class ExampleHeuristic(Heuristic):
    def get_evaluation(self, state):
        return 0
    

class HammingHeuristic(Heuristic):
    def get_evaluation(self, state):
        num = len(state)
        correct_pos = 0
        evaluation = 0
        for i in range(-1, num - 1, 1):
            if state[i] != correct_pos:
                evaluation += 1
            correct_pos += 1
        return evaluation
    

class ManhattanHeuristic(Heuristic):
    def get_evaluation(self, state):
        evaluation = 0
        n = sqrt(len(state))
        for i in range(len(state)):
            if state[i] == 0:
                continue
            x1 = i // n
            y1 = i % n
            x2 = ((state[i] - 1) % len(state)) // n
            y2 = ((state[i] - 1) % len(state)) % n
            evaluation += abs(x1 - x2) + abs(y1 - y2)
        return int(evaluation)