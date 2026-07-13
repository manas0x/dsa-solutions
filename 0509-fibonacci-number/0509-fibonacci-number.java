class Solution{
    int FibDp(int n ,Integer [] dp){
        if (n == 0 || n == 1){
            return n;
        }
        if (dp[n] != null){
            return dp[n];
        }
        return dp[n] = FibDp(n-1,dp) + FibDp(n-2,dp);
    }


    public int fib(int n) {
        Integer [] dp = new Integer[n+1];
        return FibDp(n , dp);
    }
}