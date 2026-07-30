class Solution:
    def minimumPushes(self, word: str) -> int:
        count = len(set(word))
        
        if count < 9:
            return count
        else:
            if 9 <= count and count < 17:
                return 8 + (count - 8) * 2
            elif 17 <= count and count <= 24:
                return 24 + (count - 16) * 3
            elif count == 25 or count == 26:
                return 48 + (count - 24) * 4 
        