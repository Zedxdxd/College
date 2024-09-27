import random
import time

import config

from collections import deque

from queue import PriorityQueue


class Algorithm:
    def __init__(self, heuristic=None):
        self.heuristic = heuristic
        self.nodes_evaluated = 0
        self.nodes_generated = 0

    def get_legal_actions(self, state):
        self.nodes_evaluated += 1
        max_index = len(state)
        zero_tile_ind = state.index(0)
        legal_actions = []
        if 0 <= (up_ind := (zero_tile_ind - config.N)) < max_index:
            legal_actions.append(up_ind)
        if 0 <= (right_ind := (zero_tile_ind + 1)) < max_index and right_ind % config.N:
            legal_actions.append(right_ind)
        if 0 <= (down_ind := (zero_tile_ind + config.N)) < max_index:
            legal_actions.append(down_ind)
        if 0 <= (left_ind := (zero_tile_ind - 1)) < max_index and (left_ind + 1) % config.N:
            legal_actions.append(left_ind)
        return legal_actions

    def apply_action(self, state, action):
        self.nodes_generated += 1
        copy_state = list(state)
        zero_tile_ind = state.index(0)
        copy_state[action], copy_state[zero_tile_ind] = copy_state[zero_tile_ind], copy_state[action]
        return tuple(copy_state)

    def get_steps(self, initial_state, goal_state):
        pass

    def get_solution_steps(self, initial_state, goal_state):
        begin_time = time.time()
        solution_actions = self.get_steps(initial_state, goal_state)
        print(f'Execution time in seconds: {(time.time() - begin_time):.2f} | '
              f'Nodes generated: {self.nodes_generated} | '
              f'Nodes evaluated: {self.nodes_evaluated}')
        return solution_actions


class ExampleAlgorithm(Algorithm):
    def get_steps(self, initial_state, goal_state):
        state = initial_state
        solution_actions = []
        while state != goal_state:
            legal_actions = self.get_legal_actions(state)
            action = legal_actions[random.randint(0, len(legal_actions) - 1)]
            solution_actions.append(action)
            state = self.apply_action(state, action)
        return solution_actions
    

class BreadthFirstSearchAlgorithm(Algorithm):
    def get_steps(self, initial_state, goal_state):
        if initial_state == goal_state:
            return []
        visited_states = {initial_state: True}
        queue = deque([{"state": initial_state, "actions": []}])
        while len(queue) != 0:
            curr = queue.popleft()
            actions = self.get_legal_actions(curr["state"])
            for action in actions:
                new_state = self.apply_action(curr["state"], action)
                if visited_states.get(new_state) == None:
                    visited_states[new_state] = True
                    next = {"state": new_state, "actions": curr["actions"].copy()}
                    next["actions"].append(action)
                    if new_state == goal_state:
                        return next["actions"]
                    queue.append(next)
        return []


class NodeBestFirst():
    def __init__(self, state, evaluation, actions=[]):
        self.state = state
        self.evaluation = evaluation
        self.actions = actions

    def __lt__(self, other):
        return self.evaluation < other.evaluation or self.evaluation == other.evaluation and self.state < other.state
    
    def __repr__(self):
        return "state: " + str(self.state) + " evaluation = " + str(self.evaluation) + " actions: " + str(self.actions)


class BestFirstSearchAlgorithm(Algorithm):
    def get_steps(self, initial_state, goal_state):
        if initial_state == goal_state:
            return []
        queue = PriorityQueue()
        queue.put((0, NodeBestFirst(state=initial_state, evaluation=0, actions=[])))
        visited_states = {initial_state: True}
        while queue.empty() == False:
            curr = queue.get()[1]
            if curr.state == goal_state:
                return curr.actions
            actions = self.get_legal_actions(curr.state)
            for action in actions:
                new_state = self.apply_action(curr.state, action)
                if visited_states.get(new_state) == None:
                    visited_states[new_state] = True
                    next = NodeBestFirst(state=new_state, evaluation=self.heuristic.get_evaluation(new_state), actions=curr.actions.copy())
                    next.actions.append(action)
                    queue.put((next.evaluation, next))
        return []


class NodeAStar():
    def __init__(self, state, evaluation, length, actions=[]):
        self.state = state
        self.evaluation = evaluation
        self.actions = actions
        self.length = length

    def __lt__(self, other):
        return self.evaluation + self.length < other.evaluation + other.length or self.evaluation + self.length == other.evaluation + other.length and self.state < other.state
    
    def __repr__(self):
        return "state: " + str(self.state) + " evaluation = " + str(self.evaluation) + " actions: " + str(self.actions)


class AStarAlgorithm(Algorithm):
    def get_steps(self, initial_state, goal_state):
        if initial_state == goal_state:
            return []
        queue = PriorityQueue()
        queue.put((0, NodeAStar(state=initial_state, evaluation=0, length=0, actions=[])))
        visited_states = {initial_state: True}
        while queue.empty() == False:
            curr = queue.get()[1]
            actions = self.get_legal_actions(curr.state)
            if curr.state == goal_state:
                return curr.actions
            for action in actions:
                new_state = self.apply_action(curr.state, action)
                if visited_states.get(new_state) == None:
                    visited_states[new_state] = True
                    next = NodeAStar(state=new_state, evaluation=self.heuristic.get_evaluation(new_state), length=curr.length + 1, actions=curr.actions.copy())
                    next.actions.append(action)
                    queue.put((next.evaluation + next.length, next))
        return []