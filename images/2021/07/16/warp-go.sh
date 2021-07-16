Green_font_prefix="\033[32m"
Red_font_prefix="\033[31m"
Green_background_prefix="\033[42;37m"
Red_background_prefix="\033[41;37m"
Font_color_suffix="\033[0m"
INFO="[${Green_font_prefix}INFO${Font_color_suffix}]"
ERROR="[${Red_font_prefix}ERROR${Font_color_suffix}]"

if [[ $(uname -s) != Linux ]]; then
    echo -e "${ERROR} 不支持您的系统！"
    exit 1
fi

if [[ $(id -u) != 0 ]]; then
    echo -e "${ERROR} 没有以root运行！"
    exit 1
fi


echo -e "${INFO} 系统更新中"
apt update
echo -e "${INFO} 安装必要插件中"
apt install curl sudo lsb-release -y
echo -e "${INFO} 导入原始库"
echo "deb http://deb.debian.org/debian $(lsb_release -sc)-backports main" | sudo tee /etc/apt/sources.list.d/backports.list
echo -e "${INFO} 更新中"
sudo apt update
echo -e "${INFO} 安装WireGuard插件"
sudo apt install net-tools iproute2 openresolv dnsutils -y
sudo apt install wireguard-tools --no-install-recommends
echo -e "${INFO} 安装WireGuardTools工具箱，感谢P3TERX提供的一键脚本"
curl -fsSL git.io/wireguard-go.sh | sudo bash
echo -e "${INFO} 正在从ChenYFan的脚本库拉取一件配置"
wget https://cdn.jsdelivr.net/gh/clicocc/images/images/2021/07/16/wgcf-profile.conf?PKey=null -O wgcf-profile.conf
echo -e "${INFO} 拷贝配置中"
sudo cp wgcf-profile.conf /etc/wireguard/wgcf.conf
echo -e "${INFO} 开启配置"
sudo systemctl start wg-quick@wgcf
sudo systemctl enable wg-quick@wgcf
echo 'precedence  ::ffff:0:0/96   100' | sudo tee -a /etc/gai.conf
echo -e "${INFO} Warp已开启！"