# Definition for a binary tree node.
# class TreeNode(object):
#     def __init__(self, val=0, left=None, right=None):
#         self.val = val
#         self.left = left
#         self.right = right
class Solution(object):
    def depth(self , root):
        if not root:
            return 0
        left = self.depth(root.left)
        right = self.depth(root.right)
        return 1 + max(left ,right)

    def maxDepth(self, root):
        """
        :type root: Optional[TreeNode]
        :rtype: int
        """
        return self.depth(root)
        