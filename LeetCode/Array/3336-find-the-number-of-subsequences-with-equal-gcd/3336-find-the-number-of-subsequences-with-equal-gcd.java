class Solution {
    static final int MOD = 1000000007;

    int[][][] dp;

    int gcd(int a, int b) {
        if (a == 0) return b;
        if (b == 0) return a;

        while (b != 0) {
            int t = a % b;
            a = b;
            b = t;
        }
        return a;
    }

    int solve(int i, int[] nums, int gcd1, int gcd2) {
        if (i == nums.length)
            return (gcd1 != 0 && gcd2 != 0 && gcd1 == gcd2) ? 1 : 0;

        if (dp[i][gcd1][gcd2] != -1)
            return dp[i][gcd1][gcd2];

        long skip = solve(i + 1, nums, gcd1, gcd2);
        long take1 = solve(i + 1, nums, gcd(gcd1, nums[i]), gcd2);
        long take2 = solve(i + 1, nums, gcd1, gcd(gcd2, nums[i]));

        return dp[i][gcd1][gcd2] = (int) ((skip + take1 + take2) % MOD);
    }

    public int subsequencePairCount(int[] nums) {
        dp = new int[nums.length + 1][201][201];

        for (int i = 0; i <= nums.length; i++)
            for (int j = 0; j <= 200; j++)
                Arrays.fill(dp[i][j], -1);

        return solve(0, nums, 0, 0);
    }
}