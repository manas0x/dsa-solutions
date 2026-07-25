class Solution:
    def maxProduct(self, n: int) -> int:
        result = []
        while (n > 0):
            result.append(n%10)
            n = n // 10
        
        result = sorted(result)
        return result[-2] * result[-1]