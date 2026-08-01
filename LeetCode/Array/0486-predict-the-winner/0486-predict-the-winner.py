class Solution:
    def predictTheWinner(self, nums: List[int]) -> bool:
        def dfs(arr, scoreA, scoreB, state = False):
            if len(arr) == 0:
                if scoreA >= scoreB:
                    return True
                else:
                    return False

            if state:
                left = dfs(arr[1:], scoreA, scoreB+arr[0], state = False)
                right = dfs(arr[:-1], scoreA, scoreB+arr[-1], state = False)
                return left and right
            else:
                left = dfs(arr[1:], scoreA+arr[0], scoreB, state = True)
                right = dfs(arr[:-1], scoreA+arr[-1], scoreB, state = True)
                return left or right    
        
        return dfs(nums, 0, 0)