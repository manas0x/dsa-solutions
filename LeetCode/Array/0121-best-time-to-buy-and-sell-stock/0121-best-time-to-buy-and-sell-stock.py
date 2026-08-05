class Solution:
    def maxProfit(self, prices: List[int]) -> int:
        profit , mini = 0 , float('inf')
        
        for i in prices:
            if i < mini:
                mini = i
            profit = max(profit , i-mini)

        return profit