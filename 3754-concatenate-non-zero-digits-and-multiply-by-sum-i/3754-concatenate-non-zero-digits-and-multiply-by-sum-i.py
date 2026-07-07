class Solution:
    def sumAndMultiply(self, n: int) -> int:
        if n == 0:
            return 0
            
        x = str(n).replace('0','')
        sum = 0
        for i in x:
            sum+=int(i)
        return int(x) * sum