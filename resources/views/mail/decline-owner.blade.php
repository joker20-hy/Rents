@extends('mail.layout')
@section('content')
<tr>
    <td>
        <table bgcolor="#e3342f" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #e0e0e0;border-bottom:0;border-top-left-radius:3px;border-top-right-radius:3px">
            <tbody>
                <tr>
                    <td height="72px" colspan="3"></td>
                </tr>
                <tr>
                    <td width="32px"></td>
                    <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:24px;color:#fff;line-height:1.25">
                        Từ chối đăng ký chủ trọ
                    </td><td width="32px"></td>
                </tr>
                <tr>
                    <td height="18px" colspan="3"></td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td>
        <table bgcolor="#FAFAFA" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #f0f0f0;border-bottom:1px solid #c0c0c0;border-top:0;border-bottom-left-radius:3px;border-bottom-right-radius:3px">
            <tbody>
                <tr height="16px">
                    <td width="32px" rowspan="3"></td>
                    <td></td>
                    <td width="32px" rowspan="3"></td>
                </tr>
                <tr>
                    <td>
                        <p>Thân gửi: {{ $receiver->full_name }}</p>
                        <p>Chúng tôi đã nhận được đơn đăng ký tài khoản chủ trọ trên hệ thống {{ env('APP_NAME') }} của bạn. Nhưng có một vài lý do mà đơn đăng ký của chưa thể được chấp nhận:</p>

                        <div>
                            {!! $decline_reasons !!}
                        </div>
                        
                        <p>Trân trọng!</p>
                        <p>{{ env('APP_NAME') }} team</p>
                    </td>
                </tr>
                <tr height="32px"></tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection