class Solution:
    def gcdValues(self, nums, queries):
        mx = max(nums)

        freq = [0] * (mx + 1)
        for x in nums:
            freq[x] += 1

        div_cnt = [0] * (mx + 1)
        for g in range(1, mx + 1):
            for m in range(g, mx + 1, g):
                div_cnt[g] += freq[m]

        gcd_pairs = [0] * (mx + 1)
        for g in range(mx, 0, -1):
            c = div_cnt[g]
            gcd_pairs[g] = c * (c - 1) // 2
            for m in range(g * 2, mx + 1, g):
                gcd_pairs[g] -= gcd_pairs[m]

        prefix = []
        values = []
        s = 0
        for g in range(1, mx + 1):
            if gcd_pairs[g]:
                s += gcd_pairs[g]
                values.append(g)
                prefix.append(s)

        return [values[bisect_left(prefix, q + 1)] for q in queries]