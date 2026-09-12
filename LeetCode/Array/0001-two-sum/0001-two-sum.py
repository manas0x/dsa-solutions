class Solution:
    def twoSum(self, nums: List[int], target: int) -> List[int]:
        map = {}
        n = len(nums)
        
        for i in range(n):
            map[nums[i]] = i
        
        for i in range(n):
            if target - nums[i] in map and map[target - nums[i]] != i:
                return [i,map[target - nums[i]]]
        return []