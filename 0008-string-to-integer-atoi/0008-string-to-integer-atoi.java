class Solution {
    public int myAtoi(String s) {
        int sign = 1;
        int n = s.length();
        int result = 0;
        int index = 0;

        while(index < n && s.charAt(index) == ' '){
            index++;
        }

        if (index < n && (s.charAt(index) == '+' || s.charAt(index) == '-')) {
            if (s.charAt(index) == '-') {
                sign = -1;
            }
            index++;
        }
        while (index < n && Character.isDigit(s.charAt(index))) {

            int digit = s.charAt(index) - '0';

            if (result > (Integer.MAX_VALUE - digit) / 10) {
                return sign == 1 ? Integer.MAX_VALUE : Integer.MIN_VALUE;
            }

            result = result * 10 + digit;
            index++;
        }

        return result * sign;  
    }
}