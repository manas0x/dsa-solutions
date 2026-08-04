# Definition for a binary tree node.
# class TreeNode(object):
#     def __init__(self, val=0, left=None, right=None):
#         self.val = val
#         self.left = left
#         self.right = right
class Solution(object):
    def helper(self,left,right):
        if (left is None and right is None): return True
        if (left is None or right is None): return False

        return (left.val == right.val) and self.helper(left.left, right.right) and self.helper(left.right, right.left)

    def isSymmetric(self, root):
        if not root:
            return True
        return self.helper( root.left , root.right)
