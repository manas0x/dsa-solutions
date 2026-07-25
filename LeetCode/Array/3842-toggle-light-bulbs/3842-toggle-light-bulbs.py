class Solution:
    def toggleLightBulbs(self, bulbs: list[int]) -> list[int]:
        d = {x:0 for x in bulbs}
        for i in bulbs:
            d[i] = d[i] + 1
        result = []
        for i in d:
            if (d[i] % 2 != 0):
                result.append(i)

        return sorted(result)