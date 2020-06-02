class TestClass:
    def __init__(self, foo):
        self.foo = foo

    def returnInt(self):
        return 1113

    def test(self, a, b = 'str'):
        return "a = %d, b = %s" % (a, b)

    def returnMe(self):
        return self

    def returnTuple(self):
        return (1, "two", 3.0)

    def returnList(self):
        return [1, "two", 3.0]

    def returnDict(self):
        d = {}
        d['one'] = 1
        d['two'] = 2
        d['three'] = 3
        return d

    def p(self, var):
        print var