class Solution {
    int StairsMemo(int n , Integer[] dp){
        if (n < 0) return 0;

        if (n == 0) return 1;

        if (dp[n] != null) return dp[n];

        return dp[n] = StairsMemo(n-1,dp) + StairsMemo(n-2,dp);
    }
    public int climbStairs(int n) {
        Integer[] dp = new Integer[n+1];
        return StairsMemo(n , dp);
    }
}