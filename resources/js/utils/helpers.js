export function firstName(string)
{
    return string.split(' ')[0]
}

export function textPosition(start, center, end = '')
{
    return `${start} ${center}` + (end ? ` ${end}` : '')
}

export function typeId(type, id)
{
    return `${type}-${id}`
}