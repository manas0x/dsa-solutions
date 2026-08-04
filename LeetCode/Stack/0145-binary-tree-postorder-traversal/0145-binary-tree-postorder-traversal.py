# Definition for a binary tree node.
# class TreeNode(object):
#     def __init__(self, val=0, left=None, right=None):
#         self.val = val
#         self.left = left
#         self.right = right
class Solution(object):
    def postorder(self,root,ans):
        if not root:
            return
        self.postorder(root.left,ans)
        self.postorder(root.right,ans)
        ans.append(root.val)

    def postorderTraversal(self, root):
        """
        :type root: Optional[TreeNode]
        :rtype: List[int]
        """
        ans = []
        self.postorder(root,ans)
        return ans