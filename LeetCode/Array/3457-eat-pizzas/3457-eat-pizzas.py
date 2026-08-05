from typing import List

class Solution:
    def maxWeight(self, p: List[int]) -> int:
        p.sort()
        n = len(p)
        m = n // 4
        odd = (m + 1) // 2
        even = m - odd
        
        total_weight = 0
        l = n - 1
        
        
        for _ in range(odd):
            total_weight += p[l]
            l -= 1
        
        
        for _ in range(even):
            l -= 1
            total_weight += p[l]
            l -= 1
        
        return total_weight