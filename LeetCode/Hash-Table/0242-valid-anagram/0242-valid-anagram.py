from collections import Counter
class Solution(object):
    def isAnagram(self, s, t):
        if len(s) != len(t):
            return False
        Map1 = Counter(s)
        Map2 = Counter(t)
        print(Map1,Map2)
        return Map1 == Map2