class Solution {
    public int[] findDegrees(int[][] matrix) {
        int len = matrix.length;
        int[] ans = new int[len];

        for(int i = 0 ; i < len ; i++){
            int count = 0;
            for(int j = 0 ; j < len ; j++){
                if (matrix[i][j] == 1){
                    count++;
                }
            }
            ans[i] = count;
        }
        return ans;
    }
}