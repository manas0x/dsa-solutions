class Solution(object):
    def findMissingElements(self, nums):
        mini = min(nums)
        maxi = max(nums)
        l = []
        for i in range(mini,maxi+1):
            if i not in nums:
                l.append(i)
        return l