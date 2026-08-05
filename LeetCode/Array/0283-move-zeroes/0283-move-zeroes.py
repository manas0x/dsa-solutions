class Solution:
    def moveZeroes(self, nums: List[int]) -> None:
 
        nonzero = 0
        n = len(nums)
        for i in range(n):
            if nums[i] != 0:
                nums[nonzero] = nums[i]
                nonzero+=1

        while(nonzero < n):
            nums[nonzero] = 0
            nonzero+=1
        