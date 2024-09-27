# Exceptions that might occur during the work of the fuzzer.

class MicroJavaFuzzerException(Exception):
    def __init__(self, message=""):
        super().__init__(message)

class TerminalNotInLex(MicroJavaFuzzerException):
    def __init__(self, message="Error: terminal not in the .lex file"):
        super().__init__(message)

class UnexpectedError(MicroJavaFuzzerException):
    def __init__(self, message="Unexpected error occured. Check config files"
                               "and the .cup and .lex files."):
        super().__init__(message)

class CyclicDescriptorDependency(MicroJavaFuzzerException):
    def __init__(self, message="There is a cyclic dependency between "
                                "productions in the parser specification."):
        super().__init__(message)