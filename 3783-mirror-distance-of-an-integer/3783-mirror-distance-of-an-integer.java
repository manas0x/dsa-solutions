class Solution {
    public int mirrorDistance(int n) {
        StringBuilder num = new StringBuilder(String.valueOf(n));
        num = num.reverse();
        int num2 = Integer.parseInt(num.toString());
         
         return Math.abs(n-num2);

    }
}